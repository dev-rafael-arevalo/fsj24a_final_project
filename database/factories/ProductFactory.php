<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Users;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * El nombre del modelo que esta factoría está generando.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define el estado inicial del modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(), // Nombre aleatorio para el producto
            'description' => $this->faker->sentence(), // Descripción aleatoria
            'price' => $this->faker->randomFloat(2, 10, 500), // Precio aleatorio entre 10 y 500
            'stock' => $this->faker->numberBetween(0, 100), // Stock aleatorio entre 0 y 100
            'user_id' => Users::inRandomOrder()->first()->id, // Relación con un usuario aleatorio
        ];
    }
}
