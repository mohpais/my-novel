<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class EnsureProviderVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (! $user->roles()->where('name', 'provider')->exists()) {
            return response()->json(['message' => 'Unauthorized – Not a provider'], 403);
        }

        if (! $user->provider || ! $user->provider->is_verified) {
            return response()->json(['message' => 'Your provider account is not verified yet'], 403);
        }

        return $next($request);
    }
}
