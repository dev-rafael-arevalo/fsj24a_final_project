<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * El nombre del modelo asociado a esta fábrica.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define los datos por defecto para el modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Cambia 'password' por el valor que desees
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indica que el correo no está verificado.
     *
     * @return static
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
