<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PqrController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Agrupamos las rutas bajo el middleware 'api'
Route::middleware('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Gestión de PQR
    Route::middleware('auth:api')->group(function () {
        Route::post('/pqr', [PqrController::class, 'store']); // Crear PQR
    });

    // Gestión de usuarios (solo admin)
    Route::middleware(['auth:api'])->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
        Route::put('/change-password', [UserController::class, 'changePassword']); // Cambiar contraseña
        
        // Rutas para gestionar PQR
        Route::get('/pqrs', [PqrController::class, 'index']); // Listar PQR (solo para admin)
        Route::get('/pqr/{id}', [PqrController::class, 'show']); // Ver PQR específica
        Route::delete('/pqr/{id}', [PqrController::class, 'destroy']); // Eliminar PQR
        Route::put('/pqr/{id}', [PqrController::class, 'update']);
    });
});