<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;

// Ruta para login
Route::post('login', [AuthController::class, 'login']);

// Ruta para registro de usuario sin autenticación
Route::post('register', [AuthController::class, 'register']); // Ruta para registrar un nuevo usuario

// Rutas de la API de Usuarios (v1)
Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users', [UserController::class, 'index']); // Obtener todos los usuarios
        Route::get('/users/{id}', [UserController::class, 'show']); // Obtener un usuario específico
        Route::put('/users/{id}', [UserController::class, 'update']); // Actualizar un usuario específico
        Route::delete('/users/{id}', [UserController::class, 'destroy']); // Eliminar un usuario
        Route::get('/users-stats', [UserController::class, 'stats']); // Obtener estadísticas de usuarios
        Route::get('/products', [ProductController::class, 'index']); // Listar productos
        Route::post('/products', [ProductController::class, 'store']); // Crear producto
        Route::get('/products/{id}', [ProductController::class, 'show']); // Ver un producto
        Route::put('/products/{id}', [ProductController::class, 'update']); // Actualizar producto
        Route::delete('/products/{id}', [ProductController::class, 'destroy']); // Eliminar producto
        Route::get('products/{productId}/reviews', [ReviewController::class, 'index']);
        Route::post('products/{productId}/reviews', [ReviewController::class, 'store']);
        Route::put('products/{productId}/reviews/{reviewId}', [ReviewController::class, 'update']);
        Route::delete('products/{productId}/reviews/{reviewId}', [ReviewController::class, 'destroy']);
    });
});
