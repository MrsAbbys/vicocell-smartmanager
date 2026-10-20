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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
| Utilidades para Render: Almacenamiento e Imágenes
|--------------------------------------------------------------------------
*/
Route::get('/link-storage-vicocell', function () {
    try {
        Artisan::call('storage:link');
        return response()->json([
            'status' => 'success',
            'mensaje' => 'Enlace storage:link ejecutado con exito.',
            'salida' => trim(Artisan::output())
        ], 200, [], JSON_PRETTY_PRINT);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'mensaje' => $e->getMessage()
        ], 500, [], JSON_PRETTY_PRINT);
    }
});

/*
|--------------------------------------------------------------------------
| Reparador y Activador de Base de Datos e Imágenes
|--------------------------------------------------------------------------
*/
Route::get('/setup-db-vicocell', function () {
    try {
        set_time_limit(180);

        // Crear enlace simbolico a storage si no existe
        try {
            Artisan::call('storage:link');
        } catch (\Throwable $t) {
            // Continuar si ya estaba enlazado
        }

        if (Schema::hasTable('products')) {
            // 1. Activar todos los productos existentes
            DB::table('products')->update(['is_active' => 1]);

            // 2. Asignar URLs de imagen directas a los productos si estan vacias
            $imagenesMuestra = [
                'Samsung' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=500&auto=format&fit=crop&q=60',
                'iPhone'  => 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=500&auto=format&fit=crop&q=60',
                'Xiaomi'  => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=500&auto=format&fit=crop&q=60',
                'Default' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&auto=format&fit=crop&q=60'
            ];

            $campoImg = Schema::hasColumn('products', 'image') ? 'image' : (Schema::hasColumn('products', 'imagen') ? 'imagen' : null);

            if ($campoImg) {
                $prods = DB::table('products')->get();
                foreach ($prods as $p) {
                    $nombre = $p->name ?? ($p->nombre ?? '');
                    $url = $imagenesMuestra['Default'];

                    if (stripos($nombre, 'samsung') !== false) {
                        $url = $imagenesMuestra['Samsung'];
                    } elseif (stripos($nombre, 'iphone') !== false) {
                        $url = $imagenesMuestra['iPhone'];
                    } elseif (stripos($nombre, 'xiaomi') !== false) {
                        $url = $imagenesMuestra['Xiaomi'];
                    }

                    // Si no tiene imagen o tiene ruta local rota, asignamos URL segura de muestra
                    if (empty($p->$campoImg) || !str_starts_with($p->$campoImg, 'http')) {
                        DB::table('products')->where('id', $p->id)->update([$campoImg => $url]);
                    }
                }
            }
        }

        $total = Schema::hasTable('products') ? DB::table('products')->count() : 0;
        $activos = Schema::hasTable('products') ? DB::table('products')->where('is_active', 1)->count() : 0;

        return response()->json([
            'status' => 'success',
            'mensaje' => 'Base de datos e imagenes optimizadas correctamente.',
            'total_productos' => $total,
            'productos_activos' => $activos
        ], 200, [], JSON_PRETTY_PRINT);

    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'mensaje' => $e->getMessage(),
            'linea' => $e->getFile() . ':' . $e->getLine()
        ], 200, [], JSON_PRETTY_PRINT);
    }
});
