<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        // Check Role
        $hasRole = in_array($user->role, $roles);
        
        // Check Department (if staff relationship exists)
        $hasDept = false;
        if ($user->staff && $user->staff->department) {
            $hasDept = in_array($user->staff->department->name, $roles);
        }

        if (!$hasRole && !$hasDept) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
