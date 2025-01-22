<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Verificar las credenciales del usuario
        $user = Users::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => ['Las credenciales son incorrectas.'],
            ]);
        }

        // Generar un token para el usuario
        $token = $user->createToken('LaravelFinal')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso.',
            'token' => $token
        ]);
    }
}
