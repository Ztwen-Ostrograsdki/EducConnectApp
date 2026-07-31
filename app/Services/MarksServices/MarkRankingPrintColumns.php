<?php

namespace App\Services\MarksServices;

class MarkRankingPrintColumns
{
    protected static array $baseColumns = [
        'student'      => ['type' => 'text'],
        'classe'       => ['type' => 'text'],
        'average'      => ['type' => 'mark'],
        'rang'         => ['type' => 'rank'],
        'observations' => ['type' => 'blank'],
    ];

    public static array $defaultOrder = ['student', 'classe', 'average', 'rang', 'observations'];

    protected static string $sessionKey = 'mark-ranking-selected-columns';

    /**
     * Labels résolus dynamiquement — 'average' change selon le mode
     * (matière ciblée ou moyenne semestrielle).
     */
    public static function columns(bool $subjectTargeted = true): array
    {
        $labels = [
            'student'      => 'Apprenant',
            'classe'       => 'Classe',
            'average'      => $subjectTargeted ? 'Moyenne' : 'Moy. Semestrielle',
            'rang'         => 'Rang',
            'observations' => 'Obs.',
        ];

        return collect(self::$baseColumns)
            ->map(fn (array $col, string $key) => ['label' => $labels[$key], 'type' => $col['type']])
            ->toArray();
    }

    public static function build(array $selectedKeys, bool $subjectTargeted = true): array
    {
        $columns = self::columns($subjectTargeted);

        $keys = ! empty($selectedKeys) ? $selectedKeys : self::$defaultOrder;

        return collect($keys)
            ->filter(fn (string $key) => isset($columns[$key]))
            ->values()
            ->map(fn (string $key, int $index) => [
                'key'      => $key,
                'label'    => $columns[$key]['label'],
                'type'     => $columns[$key]['type'],
                'position' => $index + 1,
            ])
            ->toArray();
    }

    public static function resolve(?array $explicit = null, bool $subjectTargeted = true): array
    {
        if (! empty($explicit)) {
            return $explicit;
        }

        return self::build(session()->get(self::$sessionKey, []), $subjectTargeted);
    }

    public static function sessionKey(): string
    {
        return self::$sessionKey;
    }
}