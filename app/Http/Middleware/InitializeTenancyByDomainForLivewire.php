<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Database\Models\Domain;

class InitializeTenancyByDomainForLivewire
{
    public function handle(Request $request, Closure $next)
    {
        $domain = $request->getHost();
        $centralDomains = config('tenancy.central_domains', []);

        if (!in_array($domain, $centralDomains)) {
            try {
                $tenantDomain = Domain::where('domain', $domain)->first();
                if ($tenantDomain) {
                    tenancy()->initialize($tenantDomain->tenant);
                    
                    // Forcer la reconnexion de la session sur la DB tenant
                    $sessionConfig = config('session');
                    $sessionConfig['connection'] = 'tenant';
                    config(['session' => $sessionConfig]);
                }
            } catch (\Exception $e) {
                //
            }
        }

        return $next($request);
    }
}