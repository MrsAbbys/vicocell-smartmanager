<?php

use App\Http\Controllers\AiChatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RepairOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Catálogo, Tienda, Asistente IA y Rastreo)
|--------------------------------------------------------------------------
*/
Route::get('/', [StoreController::class, 'index'])->name('store.index');
Route::get('/consultar-reparacion', [StoreController::class, 'trackRepair'])->name('store.track');
Route::post('/ai/chat', [AiChatController::class, 'ask'])->name('ai.chat');

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
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::resource('products', ProductController::class);
    Route::resource('sales', SaleController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('repairs', RepairOrderController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

/*
|--------------------------------------------------------------------------
| Inicializador de Base de Datos para Render
|--------------------------------------------------------------------------
*/
Route::get('/setup-db-vicocell', function () {
    try {
        // migrate:fresh limpia cualquier estructura previa y recrea todas las tablas desde cero
        Artisan::call('migrate:fresh', ['--force' => true]);
        $migOut = Artisan::output();

        Artisan::call('db:seed', ['--force' => true]);
        $seedOut = Artisan::output();

        return response()->json([
            'status' => 'success',
            'mensaje' => 'Tablas creadas y seeders ejecutados correctamente en MySQL.',
            'migrate_output' => trim($migOut),
            'seed_output' => trim($seedOut)
        ], 200, [], JSON_PRETTY_PRINT);

    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'clase' => get_class($e),
            'mensaje' => $e->getMessage(),
            'archivo' => $e->getFile() . ':' . $e->getLine()
        ], 200, [], JSON_PRETTY_PRINT);
    }
});
