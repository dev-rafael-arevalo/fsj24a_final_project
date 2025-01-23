<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Manejo del login de usuario.
     */
    public function login(Request $request)
    {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Verificar si el usuario existe
        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas.',
            ], 401);
        }

        // Generar un token con tiempo de expiración de 1 hora
        $token = $user->createToken('LaravelFinal', ['*'], now()->addHour())->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso.',
            'token' => $token,
            'expires_in' => 3600, // Tiempo en segundos
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
     /**
     * Registrar un nuevo usuario.
     */
    public function register(Request $request)
    {
        // Validación de los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Si la validación falla
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors(),
            ], 400);
        }

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Devolver respuesta de éxito
        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado con éxito',
            'data' => $user,
        ], 201);
    }
}
