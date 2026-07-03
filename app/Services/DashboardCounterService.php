<?php

// app/Services/DashboardCounterService.php
namespace App\Services;

use Illuminate\Support\Facades\Cache;

class DashboardCounterService
{
    public function get(string $key): int
    {
        $definition = config("dashboard_counters.{$key}");
        abort_unless($definition, 500, "Compteur [{$key}] non défini.");

        return Cache::tags($this->tags($definition['model']))
            ->remember("dashboard:counters:{$key}", $definition['ttl'] ?? 3600, function () use ($definition) {
                $query = $definition['model']::query();

                foreach ($definition['conditions'] ?? [] as $field => $value) {
                    $query->where($field, $value);
                }

                return $query->count();
            });
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

    protected function tags(string $modelClass): array
    {
        return ['dashboard-counters', "model:{$modelClass}"];
    }
}