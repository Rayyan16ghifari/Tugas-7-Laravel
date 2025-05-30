<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string $role
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Cek apakah user sudah login
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized - User not logged in'], 401);
        }

        // Cek role user
        if ($request->user()->role !== $role) {
            return response()->json(['message' => 'Forbidden - You do not have access'], 403);
        }

        return $next($request);
    }
}
