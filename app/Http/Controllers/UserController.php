<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller
{

    /**
     * Crear un nuevo usuario.
     */
    public function store(Request $request)
    {
        $validator = $this->validateUsers($request);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $Users = Users::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($Users, 201);
    }

    /**
     * Obtener todos los usuarios.
     */
    public function index()
    {
        $Userss = Users::all();
        return response()->json($Userss);
    }

    /**
     * Obtener un usuario específico.
     */
    public function show($id)
    {
        $Users = Users::findOrFail($id);
        return response()->json($Users);
    }

    /**
     * Actualizar un usuario específico.
     */
    public function update(Request $request, $id)
    {
        $validator = $this->validateUsers($request, $id);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $Users = Users::findOrFail($id);
        $Users->update($request->only(['name', 'email', 'password']));

        if ($request->has('password')) {
            $Users->password = Hash::make($request->password);
        }

        $Users->save();

        return response()->json($Users);
    }

    /**
     * Eliminar un usuario específico.
     */
    public function destroy($id)
    {
        $Users = Users::findOrFail($id);
        $Users->delete();

        return response()->json(['message' => 'Users deleted successfully']);
    }

    /**
     * Obtener estadísticas de los usuarios.
     */
    public function stats()
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        $UserssToday = Users::whereDate('created_at', $today)->count();
        $UserssThisWeek = Users::whereBetween('created_at', [$thisWeek, Carbon::now()])->count();
        $UserssThisMonth = Users::whereBetween('created_at', [$thisMonth, Carbon::now()])->count();

        return response()->json([
            'Users_today' => $UserssToday,
            'Users_this_week' => $UserssThisWeek,
            'Users_this_month' => $UserssThisMonth,
        ]);
    }

    /**
     * Validar los datos de entrada para crear o actualizar un usuario.
     */
    private function validateUsers(Request $request, $id = null)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:Userss,email,' . $id,
            'password' => 'required|string|min:6|confirmed',
        ]);
    }
}
