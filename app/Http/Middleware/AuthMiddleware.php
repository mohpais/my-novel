<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['message' => 'User not found'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Oops! The authentication credentials are missing or incorrect!'], 401);
        }

        return $next($request);
    }
}
