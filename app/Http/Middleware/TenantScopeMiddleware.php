<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class TenantScopeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // Apply tenant scope only if the user is authenticated
            if(auth()->user()->tenant_id) {
                // Assuming you have a way to set tenant scope, like a model static method
                Tenant::setCurrentTenantId(auth()->user()->tenant_id);
            } else {
                abort(403, 'Authenticated user lacks a tenant ID.');
            }
        }

        // Proceed with the request if no user is authenticated or the route does not need tenant scoping
        return $next($request);
    }
}
