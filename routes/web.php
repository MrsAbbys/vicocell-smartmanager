<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RepairOrderController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Catálogo, Tienda y Rastreo)
|--------------------------------------------------------------------------
*/
Route::get('/', [StoreController::class, 'index'])->name('store.index');
Route::get('/consultar-reparacion', [StoreController::class, 'trackRepair'])->name('store.track');

// Rutas de Autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas Protegidas de Clientes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/mis-compras', [CustomerOrderController::class, 'myOrders'])->name('customer.orders');
    Route::post('/comprar/{product}', [CustomerOrderController::class, 'checkoutProduct'])->name('customer.checkout');
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas de Administración (Solo Administrador y Técnico)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin,tecnico'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect()->route('products.index');
    })->name('admin.dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('repairs', RepairOrderController::class);
});
