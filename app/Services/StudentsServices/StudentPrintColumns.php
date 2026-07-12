<?php

namespace App\Services\StudentsServices;


class StudentPrintColumns
{
    public static array $columns = [
        'educMaster'       => ['label' => 'EducMaster',       'type' => 'text'],
        'full_name'        => ['label' => 'Nom & Prénom',     'type' => 'text'],
        'gender'           => ['label' => 'Sexe',             'type' => 'gender'],
        'father_full_name' => ['label' => 'Père',             'type' => 'text'],
        'mother_full_name' => ['label' => 'Mère',             'type' => 'text'],
        'classe.name'      => ['label' => 'Classe',           'type' => 'text'],
        'contacts'         => ['label' => 'Contact',          'type' => 'phone'],
        'birth_date'       => ['label' => 'Naissance / Âge',  'type' => 'age'],
        'status'           => ['label' => 'Statut',           'type' => 'badge'],
        'observations'     => ['label' => 'Obs.',             'type' => 'text'],
    ];

    public static array $defaultOrder = [
        'educMaster', 'full_name', 'gender', 'father_full_name',
        'mother_full_name', 'classe.name', 'contacts', 'birth_date',
        'status', 'observations',
    ];

    /**
     * Construit tableColumns à partir des clés sélectionnées (ordre = ordre de coche).
     * Retombe sur l'ordre par défaut si $selectedKeys est vide.
     */
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
}