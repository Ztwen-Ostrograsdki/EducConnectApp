<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Comme EnsureTenantModuleIsEnabled, mais exige TOUS les modules listés (AND).
 *
 * Usage :
 *   ->middleware('tenant.module.all:pdf_bulletins,custom_prints')
 */
class EnsureAllTenantModulesAreEnabledMiddleware
{
    public function handle(Request $request, Closure $next, string ...$modules): Response
    {
        $tenant = tenant();

        if (! $tenant) {
            abort(403, 'Espace école introuvable.');
        }

        if (! $tenant->hasActiveSubscription()) {
            abort(403, 'Aucun abonnement actif. Accès refusé.');
        }

        $missing = [];

        foreach ($modules as $module) {
            $module = trim($module);

            if ($module === '') {
                continue;
            }

            if (! $tenant->moduleIsAble($module)) {
                $missing[] = \App\Models\TenantModuleAccess::moduleLabels()[$module]['label'] ?? $module;
            }
        }

        if ($missing !== []) {
            abort(403, 'Modules requis non activés : ' . implode(', ', $missing) . '.');
        }

        return $next($request);
    }
}
