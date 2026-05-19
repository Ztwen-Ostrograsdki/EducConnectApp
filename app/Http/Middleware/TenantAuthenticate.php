<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stancl\Tenancy\Database\Models\Domain;

class TenantAuthenticate
{
    /**
     * Handle an incoming request.
     * Initialise le tenant et vérifie l'authentification via guard tenant.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // Initialiser le tenant depuis le domaine
        $domain = $request->getHost();
        $centralDomains = config('tenancy.central_domains', []);

        if (!in_array($domain, $centralDomains)) {
            try {
                $tenantDomain = Domain::where('domain', $domain)->first();
                if ($tenantDomain) {
                    tenancy()->initialize($tenantDomain->tenant);
                }
            } catch (\Exception $e) {
                //
            }
        }

        // Vérifier l'auth via guard tenant
        if (!Auth::guard('tenant')->check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}