<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return response()->json([
                'error' => 'No autenticado',
                'message' => 'Debes iniciar sesión para acceder a esta ruta.'
            ], 401);
        }

        // Verifica si el rol del usuario está dentro de los roles permitidos
        if (!in_array($request->user()->role->value, $roles)) {
            return response()->json([
                'error' => 'Acceso denegado',
                'message' => 'No tienes los permisos necesarios para ver esto.'
            ], 403);
        }

        return $next($request);
    }
}
