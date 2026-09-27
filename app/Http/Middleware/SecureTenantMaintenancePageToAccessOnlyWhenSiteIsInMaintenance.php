<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureTenantMaintenancePageToAccessOnlyWhenSiteIsInMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

         /**@var \App\Models\User */
        $user = auth('tenant')->user();

        if (!$user) return redirect()->route('login');

        $tenant = tenant();

        if(!$tenant->open_only_for_tenant) return to_route('tenant.my.profil');
        
        else return $next($request);
    }
}
