<?php

use App\Models\TenantModuleAccess;

if (! function_exists('tenantModules')) {
    /**
     * Get the module access for the current tenant.
     * Cached in memory for the duration of the request.
     */
    function tenantModules(): ?TenantModuleAccess
    {
        static $cache = null;

        if ($cache !== null) {
            return $cache;
        }

        if (! tenant()) {
            return null;
        }

        $cache = TenantModuleAccess::where('tenant_id', tenant()->id)->first();

        return $cache;
    }
}

if (! function_exists('tenantHasModule')) {
    /**
     * Check if the current tenant has a specific module enabled.
     */
    function tenantHasModule(string $module): bool
    {
        $modules = tenantModules();

        if (! $modules) {
            return false;
        }
        if (! $modules->isValid()) {
            return false;
        }

        return $modules->hasModule($module);
    }
}
