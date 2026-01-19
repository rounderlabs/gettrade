<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$rolesOrPermissions): Response
    {
        $admin = auth('admin')->user();

        if (!$admin) {
            abort(403, 'Not authenticated as admin.');
        }

        // If SuperAdmin — allow everything
        if ($admin->hasRole('SuperAdmin')) {
            return $next($request);
        }

        // Check if admin has any of the given roles or permissions
        foreach ($rolesOrPermissions as $check) {
            if ($admin->hasRole($check) || $admin->can($check)) {
                return $next($request);
            }
        }

        abort(403, 'User does not have the required role or permission.');
    }
}
