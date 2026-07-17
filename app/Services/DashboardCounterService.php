<?php

namespace App\Services;

use App\Contracts\RefreshableSchoolYearCache;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Cache;

class DashboardCounterService implements RefreshableSchoolYearCache
{
    public function get(string $key): int
    {
        $definition = $this->definition($key);

        $schoolYearId = $this->resolveCurrentSchoolYearId($definition);

        return Cache::tags($this->tags($definition['model']))
            ->remember(
                $this->cacheKey($key, $schoolYearId),
                $definition['ttl'] ?? 3600,
                fn () => $this->computeCount($definition, $schoolYearId)
            );
    }

    /** Précharge plusieurs compteurs d'un coup pour un dashboard */
    public function getMany(array $keys): array
    {
        return collect($keys)->mapWithKeys(fn ($key) => [$key => $this->get($key)])->all();
    }

    public function flushModel(string $modelClass): void
    {
        Cache::tags($this->tags($modelClass))->flush();
    }

    /**
     * Recharge tous les compteurs dépendant de l'année scolaire, pour l'année donnée.
     * Les compteurs qui n'en dépendent pas ("current_school_year" absent/false) sont ignorés.
     */
    public function refreshForSchoolYear(int $schoolYearId): void
    {
        foreach (array_keys(config('dashboard_counters', [])) as $key) {

            $definition = $this->definition($key);

            if (empty($definition['current_school_year'])) {
                continue;
            }

            $value = $this->computeCount($definition, $schoolYearId);

            Cache::tags($this->tags($definition['model']))
                ->put($this->cacheKey($key, $schoolYearId), $value, $definition['ttl'] ?? 3600);
        }
    }

    protected function definition(string $key): array
    {
        $definition = config("dashboard_counters.{$key}");

        abort_unless($definition, 500, "Compteur [{$key}] non défini.");

        return $definition;
    }

    protected function resolveCurrentSchoolYearId(array $definition): ?int
    {
        if (empty($definition['current_school_year'])) {
            return null;
        }

        return SchoolYear::current()?->first()?->id;
    }

    protected function computeCount(array $definition, ?int $schoolYearId): int
    {
        $query = $definition['model']::query();

        $conditions = $definition['conditions'] ?? [];

        if (!empty($definition['current_school_year']) && $schoolYearId) {
            $conditions['school_year_id'] = $schoolYearId;
        }

        foreach ($conditions as $field => $value) {
            $query->where($field, $value);
        }

        return $query->count();
    }

    /**
     * La clé inclut l'année UNIQUEMENT pour les compteurs qui en dépendent,
     * pour ne pas casser les clés déjà en cache pour les compteurs globaux.
     */
    protected function cacheKey(string $key, ?int $schoolYearId): string
    {
        return $schoolYearId
            ? "dashboard:counters:{$key}:sy:{$schoolYearId}"
            : "dashboard:counters:{$key}";
    }

    protected function tags(string $modelClass): array
    {
        return ['dashboard-counters', "model:{$modelClass}"];
    }
}