<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $user = Auth::user();

        // Validamos contra los roles permitidos en la ruta
        if (!in_array($user->rol, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado: No tienes los permisos de ' . implode(' o ', $roles) . ' necesarios para esta acción.'
            ], 403);
        }

        return $next($request);
    }
}
