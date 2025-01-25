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
    protected function setUp(): void
    {
        parent::setUp();

        // Crear un usuario para la autenticación
        $this->user = User::factory()->create();
    }


    // Prueba para obtener todas las reseñas de un producto
    public function test_get_reviews_by_product()
{
    // Primero, creamos el producto
    $product = Product::factory()->create();

    // Luego, creamos las reseñas asociadas a ese producto
    $reviews = Review::factory()->count(5)->create(['product_id' => $product->id]);

    // Verifica las reseñas creadas para asegurarte de que sean 5
    dd($reviews);  // Esto mostrará las reseñas creadas

    // Realizamos la solicitud GET para obtener las reseñas del producto
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->user->createToken('TestToken')->plainTextToken,
    ])->getJson('/api/v1/products/' . $product->id . '/reviews');

    // Verificamos que la respuesta tenga el código de estado 200 y que el número de reseñas sea 5
    $response->assertStatus(200)
             ->assertJsonCount(5);
}

}
