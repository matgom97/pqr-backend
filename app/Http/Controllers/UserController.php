<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Asegúrate de añadir esta línea

class UserController extends Controller
{
    // Listar todos los usuarios (solo para administradores)
    public function index()
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $users = User::all(); // Puedes aplicar paginación si lo prefieres
        return response()->json($users);
    }

    // Actualizar un usuario (solo para administradores)
    public function update(Request $request, $id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validar la solicitud
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Encuentra el usuario por ID o lanza una excepción si no existe
        $user = User::findOrFail($id);

        // Actualizar los campos del usuario
        $user->name = $request->name;
        $user->email = $request->email;

        // Si se proporciona una nueva contraseña, actualizarla
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return response()->json(['message' => 'Usuario actualizado con éxito.', 'user' => $user], 200);
    }

    // Eliminar un usuario (solo para administradores)
    public function destroy($id)
    {
        // Verificar si el usuario es administrador
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Encuentra el usuario por ID o lanza una excepción si no existe
        $user = User::findOrFail($id);

        // Verifica si el usuario que se está eliminando es el mismo que está autenticado
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'No puedes eliminar tu propio usuario.'], 403);
        }

        // Elimina el usuario
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado con éxito.'], 200);
    }

    // Método para cambiar la contraseña
    public function changePassword(Request $request)
    {
        // Verificar si el usuario está autenticado
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validación de datos
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Verificar la contraseña actual
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'La contraseña actual es incorrecta.'], 403);
        }

        // Actualizar la contraseña
        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json(['message' => 'Contraseña actualizada con éxito.'], 200);
    }
}