<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Ruta principal del catálogo / tienda
Route::get('/', function () {
    $products = [];
    if (Schema::hasTable('products')) {
        $products = DB::table('products')->get();
    }
    return view('welcome', compact('products'));
});

// Ruta de inicialización e inserción forzada de productos
Route::get('/setup-db-vicocell', function () {
    // 1. Ejecutar migraciones forzadas
    Artisan::call('migrate', ['--force' => true]);

    // 2. Si la tabla no existe o para asegurar los campos mínimos, verificar si tiene registros
    if (Schema::hasTable('products')) {
        DB::table('products')->truncate();

        // Detectar columnas existentes para evitar errores de campo no encontrado
        $hasDescription = Schema::hasColumn('products', 'description');
        $hasCategory = Schema::hasColumn('products', 'category');

        $items = [
            [
                'name' => 'Samsung Galaxy A54 5G 128GB',
                'price' => 2450.00,
                'stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Xiaomi Redmi Note 13 Pro 256GB',
                'price' => 2100.00,
                'stock' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cargador Rapido 25W Tipo C',
                'price' => 85.00,
                'stock' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Funda Antigolpes + Vidrio Templado',
                'price' => 45.00,
                'stock' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Añadir campos opcionales solo si existen en tu migración
        foreach ($items as &$item) {
            if ($hasDescription) {
                $item['description'] = 'Producto original con garantía técnica VicoCell.';
            }
            if ($hasCategory) {
                $item['category'] = str_contains($item['name'], 'Cargador') || str_contains($item['name'], 'Funda') ? 'Accesorios' : 'Celulares';
            }
        }

        DB::table('products')->insert($items);

        return response()->json([
            'status' => 'success',
            'message' => 'Base de datos migrada y 4 productos insertados exitosamente.',
            'total_productos' => DB::table('products')->count()
        ]);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'La tabla products aun no existe tras la migracion.'
    ], 500);
});