<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth; // Asegúrate de incluir esto
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        Log::info('Registro de usuario intentado:', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@(gmail\.com|hotmail\.com)$/', // Permitir solo correos de gmail o hotmail
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/', // Al menos una letra mayúscula
                'regex:/[0-9]/', // Al menos un número
                'regex:/[@$!%*?&]/', // Al menos un carácter especial
            ],
            'role' => 'required|string|in:user,admin',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Asegúrate de cifrar la contraseña
            'role' => $request->role, // Asigna el rol
        ]);

        // Generar un token JWT para el nuevo usuario
        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token], 201); // Cambié el código de estado a 201 (creado)
    }

    public function login(Request $request)
    {
        // Valida que se envíen las credenciales
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $credentials = $request->only('email', 'password');

        // Intenta generar un token usando las credenciales
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Obtener el usuario autenticado
        $user = Auth::user();

        return response()->json([
            'token' => $token,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role // Agrega el rol aquí
            ]
        ], 200);
    }
}