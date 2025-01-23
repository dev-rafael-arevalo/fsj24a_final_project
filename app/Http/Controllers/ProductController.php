<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Operaciones relacionadas con productos"
 * )
 */
class ProductController extends Controller
{
    /**
     * Obtener todos los productos.
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Users"},
     *     summary="Obtiene todos los productos",
     *     description="Obtiene una lista de todos los productos disponibles.",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error en el servidor"
     *     )
     * )
     */
    public function index()
    {
        $products = Product::all();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Crear un nuevo producto.
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Users"},
     *     summary="Crea un nuevo producto",
     *     description="Crea un nuevo producto con los datos proporcionados.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error en el servidor"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateProduct($request);

        $product = Product::create(array_merge($validatedData, [
            'user_id' => Auth::id(),
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Producto creado exitosamente.',
            'data' => $product,
        ], 201);
    }

    /**
     * Mostrar un producto específico.
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Users"},
     *     summary="Muestra un producto específico",
     *     description="Obtiene los detalles de un producto por su ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->notFoundResponse('Producto no encontrado.');
        }

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    /**
     * Actualizar un producto existente.
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Users"},
     *     summary="Actualiza un producto",
     *     description="Actualiza los datos de un producto existente.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto actualizado exitosamente",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->notFoundResponse('Producto no encontrado.');
        }

        $validatedData = $this->validateProduct($request, false);

        $product->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Producto actualizado exitosamente.',
            'data' => $product,
        ]);
    }

    /**
     * Eliminar un producto.
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Users"},
     *     summary="Elimina un producto",
     *     description="Elimina un producto existente por su ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del producto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto eliminado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->notFoundResponse('Producto no encontrado.');
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado exitosamente.',
        ]);
    }

    private function validateProduct(Request $request, $isCreate = true)
    {
        $rules = [
            'name' => $isCreate ? 'required|string|max:255' : 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => $isCreate ? 'required|numeric|min:0' : 'sometimes|numeric|min:0',
            'stock' => $isCreate ? 'required|integer|min:0' : 'sometimes|integer|min:0',
        ];

        return $request->validate($rules);
    }

    private function notFoundResponse($message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 404);
    }
}
