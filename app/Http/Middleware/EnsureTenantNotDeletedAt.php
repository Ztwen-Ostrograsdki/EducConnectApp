<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantNotDeletedAt
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenancy()->tenant;

        if (!$tenant || $tenant->deleted_at !== null) {

            abort(403, "Ce domaine est temporairement suspendu. Veuillez contacter l' administrateur!");
        }

        return $next($request);
    }
}
