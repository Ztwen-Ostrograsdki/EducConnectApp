<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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

            /**@var \App\Models\User */
            $user = auth('tenant')->user();

            if($user){

                if($user->hasRole('directeur')){

                    return to_route('tenant.subscription.request');
                    
                }
                else{

                    Auth::guard('tenant')->logout();

                    session()->invalidate();

                    session()->regenerate();

                    if(Route::currentRouteName() !== 'login'){

                        return redirect()->route('login');
                    }
                }

            }

            if(Route::currentRouteName() !== 'login'){
                
                return redirect()->route('login');
            }

            
        }

        return $next($request);
    }
}
