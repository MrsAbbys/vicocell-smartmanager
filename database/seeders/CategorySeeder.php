<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $categories = [
        ['name' => 'Celulares', 'description' => 'Smartphones y teléfonos móviles'],
        ['name' => 'Accesorios', 'description' => 'Cargadores, fundas, audífonos y protectores'],
        ['name' => 'Repuestos', 'description' => 'Pantallas, baterías, pines de carga y componentes'],
        ['name' => 'Servicios', 'description' => 'Mano de obra y mantenimiento técnico'],
    ];

    foreach ($categories as $cat) {
        \App\Models\Category::firstOrCreate(['name' => $cat['name']], $cat);
    }
}
}
