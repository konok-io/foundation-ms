<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleOrPermission
{
    public function handle(Request $request, Closure $next, string $roleOrPermission): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $roles = [];
        $permissions = [];
        
        if (str_contains($roleOrPermission, '|')) {
            $parts = explode('|', $roleOrPermission);
            foreach ($parts as $part) {
                if ($request->user()->hasRole(trim($part))) {
                    return $next($request);
                }
            }
            foreach ($parts as $part) {
                if ($request->user()->hasPermissionTo(trim($part))) {
                    return $next($request);
                }
            }
        } else {
            if ($request->user()->hasRole($roleOrPermission) || $request->user()->hasPermissionTo($roleOrPermission)) {
                return $next($request);
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        
        return redirect()->route('dashboard')->with('error', 'You do not have permission to access this resource.');
    }
}
