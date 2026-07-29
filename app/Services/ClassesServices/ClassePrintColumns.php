<?php

namespace App\Services\ClassesServices;

class ClassePrintColumns
{
    public static array $columns = [
        'name'              => ['label' => 'Classe',              'type' => 'text'],
        'filiar'            => ['label' => 'Filière',              'type' => 'text'],
        'serial'            => ['label' => 'Série',                'type' => 'text'],
        'promotion'         => ['label' => 'Promotion',            'type' => 'text'],
        'pp'                => ['label' => 'Professeur Principal', 'type' => 'text'],
        'respo1'            => ['label' => 'Responsable 1',       'type' => 'text'],
        'respo2'            => ['label' => 'Responsable 2',       'type' => 'text'],
        'effectif_total'    => ['label' => 'Effectif (T)',        'type' => 'text'],
        'effectif_garcons'  => ['label' => 'Effectif (G)',        'type' => 'text'],
        'effectif_filles'   => ['label' => 'Effectif (F)',        'type' => 'text'],
        'teachers_count'    => ['label' => 'Nb. profs',           'type' => 'text'],
        'leaves_count'      => ['label' => 'Nb. abandons',        'type' => 'text'],
        'best_student'      => ['label' => 'Meilleur élève',      'type' => 'text'],
        'worst_student'     => ['label' => 'Élève le plus faible','type' => 'text'],
        'best_boy'          => ['label' => 'Meilleur garçon',     'type' => 'text'],
        'best_girl'         => ['label' => 'Meilleure fille',     'type' => 'text'],
        'observations'      => ['label' => 'Obs.',                'type' => 'blank'],
    ];

    public static array $defaultOrder = [
        'name', 'pp',
        'respo1', 'respo2', 'effectif_total', 'teachers_count',
        'leaves_count', 'observations',
    ];

    protected static string $sessionKey = 'classe-list-selected-columns';

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
        if (! empty($explicit)) {
            return $explicit;
        }

        $sessionSelection = session()->get(self::$sessionKey, []);

        return self::build($sessionSelection);
    }

    public static function sessionKey(): string
    {
        return self::$sessionKey;
    }
}