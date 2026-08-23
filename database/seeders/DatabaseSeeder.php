<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
        ]);

        User::updateOrCreate(
            ['email' => 'admin@vicocell.com'],
            [
                'name' => 'Administrador VICOCELL',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '70000000',
            ]
        );
    }
}
