<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Crear un nuevo usuario.",
     *     description="Crea un nuevo usuario en el sistema con validaciones de email único y confirmación de contraseña.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password123"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Usuario creado exitosamente."),
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/User"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación. Se devuelve un objeto con los campos y sus errores.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="El campo nombre es obligatorio.")),
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="El email ya ha sido registrado.")),
     *                 @OA\Property(property="password", type="array", @OA\Items(type="string", example="La contraseña debe tener al menos 8 caracteres.")),
     *                 @OA\Property(property="password_confirmation", type="array", @OA\Items(type="string", example="La confirmación de contraseña no coincide.")),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor al intentar crear el usuario.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error al crear el usuario."),
     *             @OA\Property(property="error", type="string", example="Mensaje de error detallado."),
     *         ),
     *     )
     * )
     */

    public function store(Request $request)
    {
        // Validación de datos
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Retornar error de validación si falla
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Crear el usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente.',
                'data' => $user,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Obtener todos los usuarios.",
     *     description="Devuelve una lista de todos los usuarios registrados en el sistema.",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Obtener un usuario específico.",
     *     description="Devuelve los detalles de un usuario específico por su ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos del usuario.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/User"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado."),
     *         ),
     *     ),
     * )
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Actualizar un usuario específico.",
     *     description="Actualiza los datos de un usuario específico.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="John Updated"),
     *             @OA\Property(property="email", type="string", format="email", example="johnupdated@example.com"),
     *             @OA\Property(property="password", type="string", example="newpassword123"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Usuario actualizado exitosamente."),
     *             @OA\Property(property="data", ref="#/components/schemas/User"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado."),
     *         ),
     *     ),
     * )
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        // Validaciones dentro del método
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Actualizar solo los campos proporcionados
        $user->fill($request->only(['name', 'email']));

        // Si se proporciona un nuevo password, lo actualiza
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado exitosamente.',
            'data' => $user,
        ]);
    }


    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     tags={"Users"},
     *     summary="Eliminar un usuario específico.",
     *     description="Elimina un usuario específico por su ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Usuario eliminado exitosamente."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado."),
     *         ),
     *     ),
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado exitosamente.',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/users/stats",
     *     tags={"Users"},
     *     summary="Obtener estadísticas de los usuarios.",
     *     description="Devuelve estadísticas sobre la cantidad de usuarios creados hoy, esta semana y este mes.",
     *     @OA\Response(
     *         response=200,
     *         description="Estadísticas de los usuarios.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="stats", type="object",
     *                 @OA\Property(property="users_today", type="integer", example=5),
     *                 @OA\Property(property="users_this_week", type="integer", example=15),
     *                 @OA\Property(property="users_this_month", type="integer", example=50)
     *             )
     *         ),
     *     ),
     * )
     */
    public function stats()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        $usersToday = User::whereDate('created_at', $today)->count();
        $usersThisWeek = User::whereBetween('created_at', [$thisWeek, Carbon::now()])->count();
        $usersThisMonth = User::whereBetween('created_at', [$thisMonth, Carbon::now()])->count();

        return response()->json([
            'success' => true,
            'stats' => [
                'users_today' => $usersToday,
                'users_this_week' => $usersThisWeek,
                'users_this_month' => $usersThisMonth,
            ],
        ]);
    }

    /**
     * Validar los datos de entrada para crear o actualizar un usuario.
     */
    private function validateUserData(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ];

        if (!$id || $request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        return Validator::make($request->all(), $rules);
    }
}
