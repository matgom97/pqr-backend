<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar si el usuario está autenticado y es admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Si es admin, permite el acceso
        }

        // Si no es admin, retornar respuesta de error
        return response()->json(['message' => 'No tienes permiso para realizar esta acción.'], 403);
    }
}