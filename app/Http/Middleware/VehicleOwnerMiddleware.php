<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VehicleOwnerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['vehicle_owner', 'admin'])) {
            abort(403, 'Access denied. Vehicle owner accounts only.');
        }

        return $next($request);
    }
}
