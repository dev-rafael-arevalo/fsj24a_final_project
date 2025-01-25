<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    /**
     * El modelo asociado con la factory.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define el estado por defecto de la factory.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(), // Crea un producto relacionado
            'user_id' => User::factory(), // Crea un usuario relacionado
            'rating' => $this->faker->numberBetween(1, 5), // Valor entre 1 y 5
            'comment' => $this->faker->sentence(), // Comentario ficticio
        ];
    }
}
