<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class TenantHelper
{
    /**
     * Génère un slug propre depuis le nom de l'école.
     * "École Lumière" → "ecole-lumiere"
     *
     * @param string $name
     * @return string
     */
    public static function generateSlug(string $name): string
    {
        return Str::slug($name, '-');
    }

    /**
     * Génère le nom de domaine tenant selon l'environnement.
     * Dev  : "ecole-lumiere.test"
     * Prod : "ecole-lumiere.educconnect.com"
     *
     * @param string $slug
     * @return string
     */
    public static function generateDomain(string $slug): string
    {
        $tld = app()->isProduction()
            ? '.educconnect.com'
            : '.test';

        return $slug . $tld;
    }

    /**
     * Génère le nom de la DB tenant.
     * "ecole-lumiere" → "tenant_ecole_lumiere"
     *
     * @param string $slug
     * @return string
     */
    public static function generateDbName(string $slug): string
    {
        $prefix = config('tenancy.database.prefix', 'tenant_');
        return $prefix . str_replace('-', '_', $slug);
    }

    /**
     * Vérifie si un slug est disponible (pas déjà utilisé).
     *
     * @param string $slug
     * @return bool
     */
    public static function isSlugAvailable(string $slug): bool
    {
        return !\App\Models\Tenant::where('id', $slug)->exists();
    }

    /**
     * Génère un slug unique — ajoute un suffixe si déjà pris.
     * "ecole-lumiere" → "ecole-lumiere-2" si déjà pris
     *
     * @param string $name
     * @return string
     */
    public static function generateUniqueSlug(string $name): string
    {
        $slug = static::generateSlug($name);
        $original = $slug;
        $counter = 2;

        while (!static::isSlugAvailable($slug)) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}