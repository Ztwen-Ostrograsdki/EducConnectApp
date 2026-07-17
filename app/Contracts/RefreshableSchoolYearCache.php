<?php

namespace App\Contracts;

interface RefreshableSchoolYearCache
{
    /**
     * Recharge intégralement le cache de ce service pour l'année scolaire active SchoolYear::current()->first() donnée.
     * Doit écraser les données existantes (pas de lecture préalable).
     */
    public function refreshForSchoolYear(int $schoolYearId): void;
}