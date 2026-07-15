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
        'observations'     => ['label' => 'Obs.',             'type' => 'blank'],
        'emargement'       => ['label' => 'Emargement',       'type' => 'blank'],
    ];

    public static array $defaultOrder = [
        'educMaster', 'full_name', 'gender', 'classe.name', 'contacts', 'birth_date',
        'observations',
    ];

    protected static string $sessionKey = 'student-list-selected-columns';

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

    /**
     * Point d'entrée UNIQUE pour résoudre tableColumns, peu importe d'où
     * l'impression est lancée. Ordre de priorité :
     *   1. $explicit (colonnes déjà construites et passées à la main)
     *   2. Session (choix persistant de l'utilisateur, si présent)
     *   3. Ordre par défaut du service
     *
     * Ne renvoie JAMAIS un tableau vide.
     */
    public static function resolve(?array $explicit = null): array
    {
        if (! empty($explicit)) {
            return $explicit;
        }

        $sessionSelection = session()->get(self::$sessionKey, []);

        return self::build($sessionSelection);
    }
}