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
/*
|--------------------------------------------------------------------------
| Inicializador de Base de Datos para Render
|--------------------------------------------------------------------------
*/
Route::get('/setup-db-vicocell', function () {
    try {
        // 1. Ejecutar migrate:fresh para tener las tablas limpias
        Artisan::call('migrate:fresh', ['--force' => true]);

        // 2. Ejecutar seeders si existen (roles, usuarios, etc.)
        try {
            Artisan::call('db:seed', ['--force' => true]);
        } catch (\Throwable $th) {
            // Si el seeder falla, continuamos con la inserción manual
        }

        // 3. Forzar productos activos y compatibles con StoreController
        if (Schema::hasTable('products')) {
            // Si ya se crearon productos por seeder, los activamos todos:
            DB::table('products')->update(['is_active' => 1]);

            // Si la tabla quedó con 0 productos, insertamos productos de prueba con todas las columnas posibles:
            if (DB::table('products')->count() === 0) {
                $baseProduct = [
                    'name' => 'Samsung Galaxy A54 5G 128GB',
                    'description' => 'Pantalla Super AMOLED 120Hz, cámara triple 50MP, batería 5000 mAh.',
                    'price' => 2450.00,
                    'stock' => 15,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Detectar nombres alternativos en tu migración
                if (Schema::hasColumn('products', 'nombre')) $baseProduct['nombre'] = $baseProduct['name'];
                if (Schema::hasColumn('products', 'descripcion')) $baseProduct['descripcion'] = $baseProduct['description'];
                if (Schema::hasColumn('products', 'precio')) $baseProduct['precio'] = $baseProduct['price'];
                if (Schema::hasColumn('products', 'status')) $baseProduct['status'] = 'activo';

                DB::table('products')->insert([
                    $baseProduct,
                    array_merge($baseProduct, [
                        'name' => 'Xiaomi Redmi Note 13 Pro 256GB',
                        'nombre' => 'Xiaomi Redmi Note 13 Pro 256GB',
                        'description' => 'Cámara de 200MP, carga turbo 67W, 8GB RAM.',
                        'descripcion' => 'Cámara de 200MP, carga turbo 67W, 8GB RAM.',
                        'price' => 2100.00,
                        'precio' => 2100.00,
                        'stock' => 10,
                    ]),
                    array_merge($baseProduct, [
                        'name' => 'Cargador Rápido 25W Tipo C',
                        'nombre' => 'Cargador Rápido 25W Tipo C',
                        'description' => 'Cargador original con cable reforzado para carga rápida.',
                        'descripcion' => 'Cargador original con cable reforzado para carga rápida.',
                        'price' => 85.00,
                        'precio' => 85.00,
                        'stock' => 30,
                    ]),
                    array_merge($baseProduct, [
                        'name' => 'Funda Antigolpes + Vidrio 360',
                        'nombre' => 'Funda Antigolpes + Vidrio 360',
                        'description' => 'Protección completa de alto impacto para pantalla y carcasa.',
                        'descripcion' => 'Protección completa de alto impacto para pantalla y carcasa.',
                        'price' => 45.00,
                        'precio' => 45.00,
                        'stock' => 50,
                    ]),
                ]);
            }
        }

        $total = DB::table('products')->count();
        $activos = DB::table('products')->where('is_active', 1)->count();

        return response()->json([
            'status' => 'success',
            'mensaje' => 'Base de datos lista con productos activos.',
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
