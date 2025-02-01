<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear múltiples usuarios con factory
        User::factory(10)->create();  // Crear 10 usuarios aleatorios

        // Crear productos de prueba
        //Product::factory(10)->create(); // Crear 10 productos de prueba

        // Crear Reseñas de prueba
        //SSReview::factory(25)->create(); // Crear 25 reseñas de prueba
    }
}
