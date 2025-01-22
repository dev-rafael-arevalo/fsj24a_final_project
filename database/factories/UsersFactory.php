<?php

namespace Database\Factories;

use App\Models\Users;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UsersFactory extends Factory
{
    /**
     * El nombre del modelo asociado.
     */
    protected $model = Users::class;

    /**
     * Define los datos por defecto para el modelo.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Contraseña predeterminada
            'remember_token' => Str::random(10),
        ];
    }
}
