<?php

namespace App\Services\TeachersServices;

class TeacherPrintColumns
{
    public static array $columns = [
        'identifiant'      => ['label' => '#Identifiant',      'type' => 'text'],
        'full_name'      => ['label' => 'Nom & Prénom',     'type' => 'text'],
        'gender'         => ['label' => 'Sexe',             'type' => 'gender'],
        'email'          => ['label' => 'Email',            'type' => 'email'],
        'contacts'       => ['label' => 'Contact',          'type' => 'phone'],
        'classes'        => ['label' => 'Classes',          'type' => 'text'],
        'subjects'       => ['label' => 'Matières',         'type' => 'text'],
        'pp'             => ['label' => 'Est PP de',        'type' => 'text'],
        'ae'             => ['label' => 'Est AE de',        'type' => 'text'],
        'access_status'  => ['label' => 'Accès',            'type' => 'access_badge'],
        'specialties'    => ['label' => 'Spécialités',      'type' => 'list'],
        'birth_date'     => ['label' => 'Naissance / Âge',  'type' => 'age'],
        'status'         => ['label' => 'Statut',           'type' => 'badge'],
        'observations'   => ['label' => 'OBS.',             'type' => 'blank'],
        'emargement'     => ['label' => 'Emargement',       'type' => 'blank'],
    ];

    public static array $defaultOrder = [
        'identifiant', 'full_name', 'gender', 'classes', 'subjects',
        'contacts', 'observations',
    ];

    protected static string $sessionKey = 'teacher-list-selected-columns';

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
}