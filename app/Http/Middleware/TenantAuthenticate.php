<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Models\Domain;

class TenantAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        // Initialiser le tenant

        $domain = $request->getHost();
        $centralDomains = config('tenancy.central_domains', []);

        if (!in_array($domain, $centralDomains)) {
            $tenantDomain = Domain::where('domain', $domain)->first();
            if ($tenantDomain) {
                tenancy()->initialize($tenantDomain->tenant);
            }
        }

        // Vérifier l'auth
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}