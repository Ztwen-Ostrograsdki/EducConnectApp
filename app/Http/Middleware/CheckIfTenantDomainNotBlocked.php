<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Database\Models\Domain;
use Symfony\Component\HttpFoundation\Response;

class CheckIfTenantDomainNotBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Initialiser le tenant depuis le domaine
        $domain = $request->getHost();
        
        $centralDomains = config('tenancy.central_domains', []);

        if(count($centralDomains)){
            if (! in_array($domain, $centralDomains)) {
                try {
                    $tenantDomain = Domain::where('domain', $domain)->first();
                    if ($tenantDomain) {
                        tenancy()->initialize($tenantDomain->tenant);
                    }
                } catch (\Exception $e) {
                    //
                }
            }
        }

        // Vérifier l'auth via guard tenant
        if (! Auth::guard('tenant')->check()) {
            return redirect()->route('login');
        }
        else{

            $tenant = tenant();

            if($tenant->domain_blocked){

                return abort('403', "L'accès à votre espace est temporairement impossible, veuillez consulter l'administrateur!");

            }
        }

        return $next($request);
    }
}
