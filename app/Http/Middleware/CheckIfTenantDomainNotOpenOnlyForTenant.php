<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfTenantDomainNotOpenOnlyForTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenant();

        /**@var \App\Models\User */
        $user = auth('tenant')->user();

        if($user && $user->hasRole('directeur')) return $next($request);

        if($tenant->open_only_for_tenant){

            return to_route('tenant.maintenance');

            // return abort('403', "L'accès à votre espace est temporairement impossible, veuillez contacter votre directeur!");

        }
        return $next($request);
    }
}
