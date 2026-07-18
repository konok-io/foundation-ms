<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user() || !$request->user()->hasRole($role)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized. You do not have the required role.'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
