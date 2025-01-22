<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;  // Asegúrate de importar el controlador adecuado

Route::post('login', [AuthController::class, 'login']);  // Ruta para login


// Rutas de la API de Usuarios (v1)
Route::prefix('v1')->group(function () {
    Route::post('/users', [UserController::class, 'store']); // Crear usuario (público)

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users', [UserController::class, 'index']); // Obtener todos los usuarios
        Route::get('/users/{id}', [UserController::class, 'show']); // Obtener un usuario específico
        Route::put('/users/{id}', [UserController::class, 'update']); // Actualizar un usuario específico
        Route::delete('/users/{id}', [UserController::class, 'destroy']); // Eliminar un usuario
        Route::get('/users-stats', [UserController::class, 'stats']); // Obtener estadísticas de usuarios
    });
});


