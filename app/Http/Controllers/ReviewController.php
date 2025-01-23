<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Mostrar todas las reseñas de un producto específico.
     *
     * @OA\Get(
     *     path="/products/{productId}/reviews",
     *     summary="Obtener todas las reseñas de un producto",
     *     description="Este endpoint obtiene todas las reseñas asociadas a un producto específico, incluyendo información básica del usuario que las escribió.",
     *     operationId="getReviewsByProduct",
     *     tags={"Reviews"},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="ID del producto para obtener sus reseñas",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de reseñas obtenidas exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Review"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Producto no encontrado.")
     *         )
     *     )
     * )
     */
    public function index($productId)
    {
        $product = Product::findOrFail($productId);
        $reviews = $product->reviews()->with('user:id,name')->get();

        return response()->json([
            'success' => true,
            'data' => $reviews,
        ]);
    }

    /**
     * Crear una nueva reseña para un producto.
     *
     * @OA\Post(
     *     path="/products/{productId}/reviews",
     *     summary="Crear una nueva reseña para un producto",
     *     description="Este endpoint permite a un usuario autenticado crear una nueva reseña para un producto específico.",
     *     operationId="createReview",
     *     tags={"Reviews"},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="ID del producto al que se le quiere agregar una reseña",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"rating"},
     *                 @OA\Property(property="rating", type="integer", description="Puntuación de la reseña (1 a 5)", example=4),
     *                 @OA\Property(property="comment", type="string", description="Comentario de la reseña", example="Buen producto, pero le falta algo.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reseña creada exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reseña creada con éxito"),
     *             @OA\Property(property="data", ref="#/components/schemas/Review")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Datos no válidos o incompletos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados no son válidos.")
     *         )
     *     )
     * )
     */
    public function store(Request $request, $productId)
    {
        $validatedData = $this->validateReview($request);

        $product = Product::findOrFail($productId);

        $review = $product->reviews()->create([
            'user_id' => Auth::id(),
            'rating' => $validatedData['rating'],
            'comment' => $validatedData['comment'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reseña creada con éxito',
            'data' => $review,
        ], 201);
    }

    /**
     * Actualizar una reseña existente.
     *
     * @OA\Put(
     *     path="/products/{productId}/reviews/{reviewId}",
     *     summary="Actualizar una reseña existente",
     *     description="Este endpoint permite a un usuario autenticado actualizar una reseña existente de un producto.",
     *     operationId="updateReview",
     *     tags={"Reviews"},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="ID del producto al que pertenece la reseña",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="reviewId",
     *         in="path",
     *         description="ID de la reseña que se desea actualizar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"rating"},
     *                 @OA\Property(property="rating", type="integer", description="Puntuación de la reseña (1 a 5)", example=5),
     *                 @OA\Property(property="comment", type="string", description="Comentario de la reseña", example="Excelente producto.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reseña actualizada con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reseña actualizada con éxito"),
     *             @OA\Property(property="data", ref="#/components/schemas/Review")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reseña no encontrada o no pertenece al usuario autenticado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Reseña no encontrada o no tienes permisos para actualizarla.")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $productId, $reviewId)
    {
        $validatedData = $this->validateReview($request);

        $review = Review::where('product_id', $productId)
            ->where('id', $reviewId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $review->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Reseña actualizada con éxito',
            'data' => $review,
        ]);
    }

    /**
     * Eliminar una reseña.
     *
     * @OA\Delete(
     *     path="/products/{productId}/reviews/{reviewId}",
     *     summary="Eliminar una reseña",
     *     description="Este endpoint permite a un usuario autenticado eliminar una reseña existente de un producto.",
     *     operationId="deleteReview",
     *     tags={"Reviews"},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         description="ID del producto al que pertenece la reseña",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="reviewId",
     *         in="path",
     *         description="ID de la reseña que se desea eliminar",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reseña eliminada con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Reseña eliminada con éxito")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Reseña no encontrada o no pertenece al usuario autenticado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Reseña no encontrada o no tienes permisos para eliminarla.")
     *         )
     *     )
     * )
     */
    public function destroy($productId, $reviewId)
    {
        $review = Review::where('product_id', $productId)
            ->where('id', $reviewId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reseña eliminada con éxito',
        ]);
    }

    /**
     * Validar los datos de la reseña.
     */
    protected function validateReview(Request $request)
    {
        return $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);
    }
}

