<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="Crear un nuevo usuario.",
     *     description="Crea un nuevo usuario en el sistema.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
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
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="errors", type="object"),
     *         ),
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $validator = $this->validateUserData($request);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

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

        $validator = $this->validateUserData($request, $id);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->update($request->only(['name', 'email']));

        if ($request->has('password')) {
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
