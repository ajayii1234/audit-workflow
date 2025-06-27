<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  The required role name
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        // If the user is not logged in or doesn't have the required role, abort
        if (! $request->user() || ! $request->user()->hasRole($role)) {
            abort(403, 'Unauthorized.');
        }

        // Otherwise allow the request to proceed
        return $next($request);
    }
}
