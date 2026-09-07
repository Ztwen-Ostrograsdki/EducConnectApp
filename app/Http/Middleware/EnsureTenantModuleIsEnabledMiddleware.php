<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloque l'accès si le(s) module(s) demandé(s) ne sont pas actifs
 * sur la subscription active du tenant courant.
 *
 * Usage routes :
 *   ->middleware('tenant.module:timetable')
 *   ->middleware('tenant.module:pdf_bulletins,marks_management')  // OR logique
 *
 * Usage Livewire full-page :
 *   #[Middleware('tenant.module:parent_portal')]
 */
class EnsureTenantModuleIsEnabledMiddleware
{
    /**
     * @param  string  ...$modules  Clés modules (ex: timetable, pdf_bulletins)
     *                              Si plusieurs : accès autorisé si AU MOINS un est actif (OR).
     */
    public function handle(Request $request, Closure $next, string ...$modules): Response
    {
        $tenant = tenant();

        if (! $tenant) {
            abort(403, 'Espace école introuvable.');
        }

        // Aucun module passé → on exige seulement une subscription active
        if ($modules === []) {
            if (! $tenant->hasActiveSubscription()) {
                abort(403, 'Aucun abonnement actif. Accès refusé.');
            }

            return $next($request);
        }

        foreach ($modules as $module) {
            $module = trim($module);

            if ($module === '') {
                continue;
            }

            if ($tenant->moduleIsAble($module)) {
                return $next($request);
            }
        }

        $labels = collect($modules)
            ->map(fn (string $m) => \App\Models\TenantModuleAccess::moduleLabels()[trim($m)]['label'] ?? trim($m))
            ->filter()
            ->implode(', ');

        abort(403, $labels !== ''
            ? "Module non activé pour votre abonnement : {$labels}."
            : 'Module non activé pour votre abonnement.');
    }
}
