<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('tenant_id')) {
            return $next($request);
        }

        $tenant = Tenant::where('is_active', true)->first();

        if ($tenant) {
            session(['tenant_id' => $tenant->id]);
        }

        return $next($request);
    }
}
