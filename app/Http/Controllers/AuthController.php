<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Muestra la vista de inicio de sesión.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('products.index')
                : redirect()->route('store.index');
        }
        return view('auth.login');
    }

    /**
     * Procesa la autenticación y redirige según el rol.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isAdmin() || $user->isTecnico()) {
                return redirect()->intended(route('products.index'))->with('success', 'Bienvenido al panel administrativo, ' . $user->name . '.');
            }

            return redirect()->intended(route('store.index'))->with('success', '¡Hola, ' . $user->name . '! Bienvenido a tu cuenta VICOCELL.');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Muestra la vista de registro para clientes.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('store.index');
        }
        return view('auth.register');
    }

    /**
     * Registra una nueva cuenta de cliente.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'cliente',
        ]);

        Auth::login($user);

        return redirect()->route('store.index')->with('success', '¡Tu cuenta ha sido creada exitosamente!');
    }

    /**
     * Cierra la sesión activa.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('store.index')->with('success', 'Has cerrado sesión correctamente.');
    }
}
