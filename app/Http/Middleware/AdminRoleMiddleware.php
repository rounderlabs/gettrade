<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $admin = auth('admin')->user();

        if (! $admin) {
            abort(Response::HTTP_FORBIDDEN, 'Not authenticated as admin.');
        }

        // Check if user has *any* of the given roles
        if (! $admin->hasAnyRole($roles)) {
            abort(Response::HTTP_FORBIDDEN, 'User does not have the required role.');
        }

        return $next($request);
    }
}
