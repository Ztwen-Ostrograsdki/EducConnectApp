<?php

namespace App\Services\MarksServices;

class MarkDiagnosticColumns
{
    public static array $columns = [
        'teacher'                    => ['label' => 'Enseignant',              'type' => 'text'],
        'classe'                     => ['label' => 'Classe',                  'type' => 'text'],
        'subject'                    => ['label' => 'Matière',                 'type' => 'text'],
        'checked_types'              => ['label' => 'Types vérifiés',          'type' => 'text'],
        'status'                     => ['label' => 'Statut',                  'type' => 'badge'],
        'students_without_count'    => ['label' => 'Nb. sans notes',          'type' => 'text'],
        'students_without_list'      => ['label' => 'Apprenants sans notes',   'type' => 'text'],
    ];

    public static array $defaultOrder = ['teacher', 'classe', 'subject', 'status', 'students_without_count'];

    protected static string $sessionKey = 'mark-diagnostic-selected-columns';

    public static function build(array $selectedKeys): array
    {
        $keys = ! empty($selectedKeys) ? $selectedKeys : self::$defaultOrder;

        return collect($keys)
            ->filter(fn (string $key) => isset(self::$columns[$key]))
            ->values()
            ->map(fn (string $key, int $index) => [
                'key'      => $key,
                'label'    => self::$columns[$key]['label'],
                'type'     => self::$columns[$key]['type'],
                'position' => $index + 1,
            ])
            ->toArray();
    }

    public static function resolve(?array $explicit = null): array
    {
        if (! empty($explicit)) return $explicit;

        return self::build(session()->get(self::$sessionKey, []));
    }

    public static function sessionKey(): string
    {
        return self::$sessionKey;
    }
}