<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // if (! $user->roles()->whereIn('code', $roles)->exists()) {
        //     return response()->json(['message' => 'Forbidden - you do not have the required role'], 403);
        // }

        // Cek apakah role user ada di list role yang diizinkan
        if (!in_array($user->role->code, $roles)) {
            return response()->json(['message' => 'Forbidden - you do not have the required role'], 403);
        }

        return $next($request);
    }
}
