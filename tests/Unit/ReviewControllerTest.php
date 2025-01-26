<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    // Set up un usuario antes de cada prueba
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }


    public function test_get_reviews_by_product()
    {
        // Crear usuario autenticado
        $this->user = User::factory()->create();

        // Crear producto
        $product = Product::factory()->create();

        // Crear 5 reseñas asociadas al producto
        $reviews = Review::factory()->count(5)->create(['product_id' => $product->id]);

        // Verifica las reseñas creadas
        $this->assertCount(5, $reviews);

        // Generar token de autenticación
        $token = $this->user->createToken('TestToken')->plainTextToken;

        // Realizar la solicitud GET para obtener las reseñas
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/products/' . $product->id . '/reviews');

        // Validar respuesta
        $response->assertStatus(200)
                 ->assertJsonCount(5); // Asegura que hay 5 reseñas en la respuesta
    }


}
