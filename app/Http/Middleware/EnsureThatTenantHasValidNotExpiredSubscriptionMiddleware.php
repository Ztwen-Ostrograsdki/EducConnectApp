<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureThatTenantHasValidNotExpiredSubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = tenant();

        if(!$tenant){

            return abort('403', "Cet espace est temporairement indisponible ou n'existe plus , veuillez contacter votre directeur!");

        }


        if(!$tenant->hasActiveSubscription()){

            return to_route('tenant.subscription.request');
        }

        return $next($request);
    }
}
