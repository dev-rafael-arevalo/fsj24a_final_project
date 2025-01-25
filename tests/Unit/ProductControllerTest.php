<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    // Set up un usuario antes de cada prueba
    protected function setUp(): void
    {
        parent::setUp();

        // Crear un usuario para la autenticación
        $this->user = User::factory()->create();
    }

    // Test para crear un producto
    public function test_it_creates_a_new_product()
    {
        // Datos de prueba, incluyendo el user_id
        $payload = [
            'name' => 'New Product',
            'description' => 'Product Description',
            'price' => 100,
            'stock' => 10,
            'user_id' => $this->user->id,  // Agregar el user_id
        ];

        // Realizar la petición POST para crear el producto con autenticación
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->createToken('TestToken')->plainTextToken,
        ])->postJson('/api/v1/products', $payload);

        // Verificar que el producto se haya creado correctamente
        $response->assertCreated()
                 ->assertJson([
                     'success' => true,
                     'message' => 'Producto creado exitosamente.',
                     'data' => [
                         'name' => 'New Product',
                         'description' => 'Product Description',
                         'price' => 100,
                         'stock' => 10,
                     ]
                 ]);
    }

    // Test para obtener un producto específico
    public function test_it_returns_a_specific_product()
    {
        $product = Product::factory()->create([
            'user_id' => $this->user->id,  // Relacionar con un usuario
        ]);

        // Realizar la petición GET para obtener el producto
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->createToken('TestToken')->plainTextToken,
        ])->getJson('/api/v1/products/' . $product->id);

        // Verificar la respuesta
        $response->assertOk()
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id' => $product->id,
                         'name' => $product->name,
                         'description' => $product->description,
                         'price' => $product->price,
                         'stock' => $product->stock,
                     ]
                 ]);
    }

    // Test para actualizar un producto
    public function test_it_updates_a_product()
    {
        $product = Product::factory()->create([
            'user_id' => $this->user->id,  // Relacionar con un usuario
        ]);

        $payload = [
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 150,
            'stock' => 20,
            'user_id' => $this->user->id,  // Asegurarse de enviar el user_id
        ];

        // Realizar la petición PUT para actualizar el producto
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->createToken('TestToken')->plainTextToken,
        ])->putJson('/api/v1/products/' . $product->id, $payload);

        // Verificar que el producto se haya actualizado correctamente
        $response->assertOk()
                 ->assertJson([
                     'success' => true,
                     'message' => 'Producto actualizado exitosamente.',
                     'data' => [
                         'name' => 'Updated Product',
                         'description' => 'Updated description',
                         'price' => 150,
                         'stock' => 20,
                     ]
                 ]);
    }

    // Test para eliminar un producto
    public function test_it_deletes_a_product()
    {
        $product = Product::factory()->create([
            'user_id' => $this->user->id,  // Relacionar con un usuario
        ]);

        // Realizar la petición DELETE para eliminar el producto
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->createToken('TestToken')->plainTextToken,
        ])->deleteJson('/api/v1/products/' . $product->id);

        // Verificar que el producto se haya eliminado correctamente
        $response->assertNoContent();
    }

    // Test para manejar un error 404 si el producto no existe
    public function test_it_returns_404_if_product_not_found()
    {
        // Realizar la petición GET para obtener un producto que no existe
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->createToken('TestToken')->plainTextToken,
        ])->getJson('/api/v1/products/9999');

        // Verificar que la respuesta sea un error 404
        $response->assertNotFound()
                 ->assertJson([
                     'success' => false,
                     'message' => 'Producto no encontrado.',
                 ]);
    }
}
