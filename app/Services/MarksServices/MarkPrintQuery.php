<?php

namespace App\Services\MarksServices;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Student;
use App\Models\Subject;
use App\Services\MarksServices\ClasseSubjectMarksCacheService;
use App\Services\MarksServices\MarksPrintFormatter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MarkPrintQuery
{
    protected const INTERRO_TYPES = ['interro1', 'interro2', 'interro3', 'interro4'];

    public static function devoirColumns(string $devoirsType): array
    {
        return $devoirsType === 'devoir1-compo'
            ? ['devoir1' => 'Devoir 1', 'compo' => 'Composition']
            : ['devoir1' => 'Devoir 1', 'devoir2' => 'Devoir 2'];
    }

    /**
     * Classes ciblées par le scope de filtres (promotion, filière, série, classe unique,
     * niveau). Si un subject_id est fourni, ne garde que les classes où cette matière
     * est effectivement enseignée cette année.
     */
    public static function classesQuery(array $config, int $schoolYearId): Builder
    {
        $query = Classe::where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->with(['filiar', 'serial', 'promotion']);

        if (! empty($config['classe_id']))    $query->where('id', $config['classe_id']);
        if (! empty($config['filiar_id']))    $query->where('filiar_id', $config['filiar_id']);
        if (! empty($config['serial_id']))    $query->where('serial_id', $config['serial_id']);
        if (! empty($config['promotion_id'])) $query->where('promotion_id', $config['promotion_id']);
        if (! empty($config['level']))        $query->where('level', $config['level']);

        if (! empty($config['promotionInGroups'])) {
            $query->whereHas('promotion', fn ($q) => $q->where('name', $config['promotionInGroups']));
        }

        if (! empty($config['subject_id'])) {
            $query->whereHas('classeSubjects', fn ($q) =>
                $q->where('subject_id', $config['subject_id'])
                  ->where('is_active', true)
                  ->whereNull('ended_at')
            );
        }

        return $query->orderBy('name');
    }

    public static function count(array $config, int $schoolYearId): int
    {
        return self::classesQuery($config, $schoolYearId)->count();
    }

    /**
     * Élèves actifs/abandons d'une classe, selon leavesConfig
     * (mêmes conventions que StudentPrintQuery : onlyActives / onlyLeaves / withLeaves).
     */
    protected static function studentsQuery(Classe $classe, array $config): Builder
    {
        $query = Student::whereHas('yearlyClasseStudents', fn ($q) =>
            $q->where('classe_id', $classe->id)
              ->where('school_year_id', $classe->school_year_id)
              ->where('is_active', true)
        );

        match ($config['leavesConfig'] ?? 'onlyActives') {
            'onlyLeaves' => $query->whereHas('yearlyStudentsLeaves', fn ($q) =>
                $q->where('school_year_id', $classe->school_year_id)
                  ->where('classe_id', $classe->id)
                  ->whereNull('ended_at')
            ),
            'onlyActives' => $query->whereDoesntHave('yearlyStudentsLeaves', fn ($q) =>
                $q->where('school_year_id', $classe->school_year_id)
                  ->where('classe_id', $classe->id)
                  ->whereNull('ended_at')
            ),
            'withLeaves' => $query->where('is_active', true),
			default => $query->whereHas('yearlyStudentsLeaves', fn ($q) =>
                $q->where('school_year_id', $classe->school_year_id)
                  ->where('classe_id', $classe->id)
                  ->whereNull('ended_at'))
        };

        return $query->orderBy('name')->orderBy('prenames');
    }

    /**
     * Matières prises en compte pour une classe :
     * - si $subjectId fourni, uniquement celle-là (avec son coefficient dans cette classe)
     * - sinon, toutes les matières actives enseignées dans la classe cette année
     *   (nécessaire pour calculer moy_semestrielle = Σ(moy_coef) / Σ(coef))
     */
    protected static function subjectsForClasse(Classe $classe, ?int $subjectId, int $schoolYearId): Collection
    {
        $query = ClasseSubjectOfSchoolYear::with('subject')
            ->where('classe_id', $classe->id)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at');

        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }

        return $query->get(['id', 'classe_id', 'subject_id', 'coefficient'])
            ->filter(fn ($cs) => $cs->subject !== null);
    }

    /**
     * Calcule moy/moy_coef d'un élève pour UNE matière, à partir du cache de notes déjà
     * chargé pour cette (classe, matière, période).
     */
    protected static function computeSubjectMoy(array $studentMarks, array $devoirColumns, float $coefficient): array
    {
        $values = [];
        foreach (array_merge(self::INTERRO_TYPES, $devoirColumns) as $type) {
            $values[$type] = $studentMarks[$type]['value'] ?? null;
        }

        $interroValues = array_filter(array_intersect_key($values, array_flip(self::INTERRO_TYPES)), fn ($v) => ! is_null($v));
        $moyInterro = ! empty($interroValues) ? round(array_sum($interroValues) / count($interroValues), 2) : null;

        $devoirValues = array_filter(array_intersect_key($values, array_flip($devoirColumns)), fn ($v) => ! is_null($v));
        $moyDevoirs = ! empty($devoirValues) ? round(array_sum($devoirValues) / count($devoirValues), 2) : null;

        $moy = match (true) {
            ! is_null($moyInterro) && ! is_null($moyDevoirs) => round(($moyInterro + $moyDevoirs) / 2, 2),
            ! is_null($moyInterro) => $moyInterro,
            ! is_null($moyDevoirs) => $moyDevoirs,
            default => null,
        };

        $moyCoef = ! is_null($moy) ? round($moy * $coefficient, 2) : null;

        return compact('values', 'moyInterro', 'moy', 'moyCoef');
    }

    protected static function rankBy(array $rows, string $key): array
    {
        $ranked = collect($rows)->sortByDesc(fn ($row) => $row[$key] ?? -1)->values();

        $rank = 0; $lastVal = null; $position = 0;

        return $ranked->map(function ($row) use (&$rank, &$lastVal, &$position, $key) {
            $position++;

            if (is_null($row[$key] ?? null)) {
                $row['rank'] = null;
                return $row;
            }

            if ($row[$key] !== $lastVal) {
                $rank = $position;
                $lastVal = $row[$key];
            }

            $row['rank'] = $rank;
            return $row;
        })->all();
    }

    /**
     * Point d'entrée principal — boucle sur les classes du scope, calcule pour chaque
     * élève soit le détail d'une matière (subject_id fourni), soit la moyenne
     * semestrielle agrégée sur toutes les matières (subject_id absent).
     */
    public static function getFormattedRows(
        array $config,
        int $schoolYearId,
        int $period,
        array $tableColumns,
        string $devoirsType,
        ?int $subjectId
    ): array {
        $devoirColumns = array_keys(self::devoirColumns($devoirsType));
        $cacheService = app(ClasseSubjectMarksCacheService::class);

        $allRows = [];

        foreach (self::classesQuery($config, $schoolYearId)->cursor() as $classe) {
            $classeLabel = $classe->code ?: $classe->name;

            $subjects = self::subjectsForClasse($classe, $subjectId, $schoolYearId);

            if ($subjects->isEmpty()) {
                unset($classe);
                continue;
            }

            $classeRows = [];

            foreach (self::studentsQuery($classe, $config)->cursor() as $student) {

                if ($subjectId) {
                    // ─── Mode matière unique : détail interro/devoirs/moy/moy_coef ──
                    $cs = $subjects->first();
                    $marksData = $cacheService->get($classe->id, $cs->subject_id, $period, $schoolYearId);
                    $studentMarks = $marksData[$student->id] ?? [];

                    $computed = self::computeSubjectMoy($studentMarks, $devoirColumns, (float) $cs->coefficient);

                    $classeRows[] = [
                        'student'          => $student,
                        'classeLabel'      => $classeLabel,
                        'values'           => $computed['values'],
                        'moy_interro'      => $computed['moyInterro'],
                        'moy'              => $computed['moy'],
                        'moy_coef'         => $computed['moyCoef'],
                        'moy_semestrielle' => null,
                        'rank_key'         => 'moy',
                    ];
                } else {
                    // ─── Mode toutes matières : moy_semestrielle = Σmoy_coef / Σcoef ──
                    $sumMoyCoef = 0.0;
                    $sumCoef = 0.0;
                    $hasAnyMark = false;

                    foreach ($subjects as $cs) {
                        $marksData = $cacheService->get($classe->id, $cs->subject_id, $period, $schoolYearId);
                        $studentMarks = $marksData[$student->id] ?? [];

                        $computed = self::computeSubjectMoy($studentMarks, $devoirColumns, (float) $cs->coefficient);

                        if (! is_null($computed['moyCoef'])) {
                            $sumMoyCoef += $computed['moyCoef'];
                            $sumCoef += (float) $cs->coefficient;
                            $hasAnyMark = true;
                        }
                    }

                    $moySemestrielle = ($hasAnyMark && $sumCoef > 0) ? round($sumMoyCoef / $sumCoef, 2) : null;

                    $classeRows[] = [
                        'student'          => $student,
                        'classeLabel'      => $classeLabel,
                        'values'           => [],
                        'moy_interro'      => null,
                        'moy'              => null,
                        'moy_coef'         => null,
                        'moy_semestrielle' => $moySemestrielle,
                        'rank_key'         => 'moy_semestrielle',
                    ];
                }

                unset($student);
            }

            // Rang calculé PAR CLASSE (pas globalement sur tout le scope)
            $rankKey = $subjectId ? 'moy' : 'moy_semestrielle';
            $classeRows = self::rankBy($classeRows, $rankKey);

            $allRows = array_merge($allRows, $classeRows);

            unset($classe);
        }

        // ─── Construction finale formatée pour la vue ──
        $rows = [];

        foreach ($allRows as $index => $row) {
            $context = array_merge($row['values'], [
                'classeLabel'      => $row['classeLabel'],
                'moy_interro'      => $row['moy_interro'],
                'moy'              => $row['moy'],
                'moy_coef'         => $row['moy_coef'],
                'moy_semestrielle' => $row['moy_semestrielle'],
                'rang'             => $row['rank'],
            ]);

            $rows[] = [
                'index' => $index + 1,
                'cells' => collect($tableColumns)
                    ->mapWithKeys(fn (array $col) => [
                        $col['key'] => MarksPrintFormatter::getData($row['student'], $col, $context),
                    ])
                    ->toArray(),
            ];
        }

        return $rows;
    }

    public static function resolveDocTitle(array $config, ?int $subjectId = null, ?int $period = null, ?int $schoolYearId = null): string
    {
        $doc_title = 'Liste des notes';

        if ($subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) $doc_title .= " en {$subject->name}";
        } else {
            $doc_title .= ' (moyennes semestrielles, toutes matières)';
        }

        if ($period && $schoolYearId) {

            if(!$schoolYearId){

                $schoolYear = SchoolYear::current()->first();
            }
            else{

                $schoolYear = SchoolYear::find($schoolYearId);
            }

            $doc_title .= " - {$schoolYear->periodLabel()} {$period}";
        }

        if (! empty($config['classe_id'])) {
            $classe = Classe::find($config['classe_id']);
            if ($classe) $doc_title .= " de la classe {$classe->name}";
        }

        if (! empty($config['filiar_id'])) {
            $filiar = Filiar::find($config['filiar_id']);
            if ($filiar) $doc_title .= " de la filière {$filiar->name}";
        }

        if (! empty($config['serial_id'])) {
            $serial = Serial::find($config['serial_id']);
            if ($serial) $doc_title .= " de la série {$serial->name}";
        }

        if (! empty($config['promotion_id'])) {
            $promo = Promotion::find($config['promotion_id']);
            if ($promo) $doc_title .= " de la promotion {$promo->name}";
        }

        if (! empty($config['promotionInGroups'])) {
            $doc_title .= " de la promotion {$config['promotionInGroups']}";
        }

        match ($config['leavesConfig'] ?? 'onlyActives') {
            'onlyLeaves' => $doc_title .= ' (abandons uniquement)',
            'withLeaves' => $doc_title .= ' (abandons inclus)',
            default      => null,
        };

        return $doc_title;
    }
}