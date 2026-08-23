<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Maneja la verificación de roles permitidos para la ruta.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            // Si es cliente intentando acceder al panel de administración, redirigir a la tienda
            return redirect()->route('store.index')->with('error', 'Acceso denegado: No tienes permisos para acceder al panel administrativo.');
        }

        return $next($request);
    }
}
