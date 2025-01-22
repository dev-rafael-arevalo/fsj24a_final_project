<?php

namespace Database\Seeders;

use App\Models\Users;
use App\Models\Product; // Agregamos el modelo de Product
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear múltiples usuarios con factory
        Users::factory(10)->create();  // Crear 10 usuarios aleatorios

        // Crear productos de prueba
        Product::factory(10)->create(); // Crear 10 productos de prueba
    }
}
