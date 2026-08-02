<?php

namespace App\Services\MarksServices;


use Illuminate\Support\Collection;

class SubjectAverageCalculator
{
    protected const INTERRO_TYPES = ['interro1', 'interro2', 'interro3', 'interro4'];

    public static function devoirColumns(): array
    {
        $devoirsType = tenant()->devoirs_type ?? 'devoir1-devoir2';

        return $devoirsType === 'devoir1-compo'
            ? ['devoir1', 'compo']
            : ['devoir1', 'devoir2'];
    }

    public static function moyInterro(array $studentMarks): ?float
    {
        $values = collect(self::INTERRO_TYPES)
            ->map(fn ($type) => $studentMarks[$type]['value'] ?? null)
            ->filter(fn ($v) => !is_null($v));

        return $values->isNotEmpty() ? round($values->avg(), 2) : null;
    }

    public static function moyDevoirs(array $studentMarks, ?array $devoirColumns = null): ?float
    {
        $devoirColumns ??= self::devoirColumns();

        $values = collect($devoirColumns)
            ->map(fn ($type) => $studentMarks[$type]['value'] ?? null)
            ->filter(fn ($v) => !is_null($v));

        return $values->isNotEmpty() ? round($values->avg(), 2) : null;
    }

    /**
     * Moyenne de matière : (moyInterro + moyDevoirs) / 2 si les deux existent,
     * sinon celle qui existe, sinon null.
     */
    public static function moy(array $studentMarks, ?array $devoirColumns = null): ?float
    {
        $moyInterro = self::moyInterro($studentMarks);
        $moyDevoirs = self::moyDevoirs($studentMarks, $devoirColumns);

        if (!is_null($moyInterro) && !is_null($moyDevoirs)) {
            return round(($moyInterro + $moyDevoirs) / 2, 2);
        }

        return $moyInterro ?? $moyDevoirs ?? null;
    }

    public static function moyCoef(?float $moy, float $coefficient): ?float
    {
        return !is_null($moy) ? round($moy * $coefficient, 2) : null;
    }

    /**
     * Compte brut des notes obtenues (toutes types confondus) et celles réussies (>= 10),
     * à partir du tableau 'marks' déjà présent dans le cache pour un apprenant/matière.
     *
     * @return array{total: int, success: int}
     */
    public static function successCounts(array $marksForStudent): array
    {
        $values = collect($marksForStudent)
            ->pluck('value')
            ->filter(fn ($v) => !is_null($v));

        return [
            'total'   => $values->count(),
            'success' => $values->filter(fn ($v) => $v >= 10)->count(),
        ];
    }

    /**
     * Pourcentage de notes réussies (>= 10) sur l'ensemble des notes obtenues
     * (interros + devoirs confondus) pour un apprenant dans une matière.
     * Retourne null si aucune note n'a été saisie (pas de division par zéro).
     */
    public static function getSuccessPercentage(array $marksForStudent, ?array $devoirColumns = null): ?float
    {
        $counts = self::successCounts($marksForStudent);

        return $counts['total'] > 0
            ? round(($counts['success'] / $counts['total']) * 100, 2)
            : null;
    }
}