<?php

namespace App\Services\MarksServices;

class MarkPrintColumns
{
    public static array $columns = [
        'student'          => ['label' => 'Apprenant',          'type' => 'text'],
        'classe'           => ['label' => 'Classe',             'type' => 'text'],
        'interro1'         => ['label' => 'Int. 1',             'type' => 'mark'],
        'interro2'         => ['label' => 'Int. 2',             'type' => 'mark'],
        'interro3'         => ['label' => 'Int. 3',             'type' => 'mark'],
        'interro4'         => ['label' => 'Int. 4',             'type' => 'mark'],
        'moy_interro'      => ['label' => 'Moy. Int',           'type' => 'mark'],
        'devoir1'          => ['label' => 'Devoir 1',           'type' => 'mark'],
        'devoir2'          => ['label' => 'Devoir 2',           'type' => 'mark'],
        'compo'            => ['label' => 'Composition',        'type' => 'mark'],
        'moy'              => ['label' => 'Moyenne',            'type' => 'mark'],
        'moy_coef'         => ['label' => 'Moy. Coef.',         'type' => 'mark'],
        'moy_semestrielle' => ['label' => 'Moy. Semestrielle',  'type' => 'mark'],
        'rang'             => ['label' => 'Rang',               'type' => 'rank'],
        'observations'     => ['label' => 'Obs.',               'type' => 'blank'],
    ];

    protected static string $sessionKey = 'mark-list-selected-columns';

    /**
     * L'ordre par défaut dépend du type de devoirs du tenant (devoir1-devoir2
     * vs devoir1-compo), donc pas de tableau statique — calculé dynamiquement.
     */
    public static function defaultOrder(string $devoirsType = 'devoir1-devoir2', bool $subjectTargeted = true): array
    {
        if (! $subjectTargeted) {
            // Sans matière ciblée : seule la moyenne semestrielle a du sens
            return ['student', 'classe', 'moy_semestrielle', 'rang', 'observations'];
        }

        $devoirKeys = $devoirsType === 'devoir1-compo'
            ? ['devoir1', 'compo']
            : ['devoir1', 'devoir2'];

        return array_merge(
            ['student', 'classe', 'interro1', 'interro2', 'interro3', 'interro4', 'moy_interro'],
            $devoirKeys,
            ['moy', 'moy_coef', 'rang', 'observations']
        );
    }

    public static function build(array $selectedKeys, string $devoirsType = 'devoir1-devoir2', bool $subjectTargeted = true): array
    {
        $keys = ! empty($selectedKeys) ? $selectedKeys : self::defaultOrder($devoirsType, $subjectTargeted);

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

    public static function resolve(?array $explicit = null, string $devoirsType = 'devoir1-devoir2', bool $subjectTargeted = true): array
    {
        if (! empty($explicit)) {
            return $explicit;
        }

        $sessionSelection = session()->get(self::$sessionKey, []);

        return self::build($sessionSelection, $devoirsType, $subjectTargeted);
    }

    public static function sessionKey(): string
    {
        return self::$sessionKey;
    }
}