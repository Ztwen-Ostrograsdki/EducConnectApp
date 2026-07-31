<?php

namespace App\Services\MarksServices;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MarkRankingPrintQuery
{
    protected static function classesQuery(array $config, int $schoolYearId)
    {
        $query = Classe::where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->with(['filiar', 'serial', 'promotion']);

        if (! empty($config['classe_id']))       $query->where('id', $config['classe_id']);
        if (! empty($config['filiar_id']))       $query->where('filiar_id', $config['filiar_id']);
        if (! empty($config['serial_id']))       $query->where('serial_id', $config['serial_id']);
        if (! empty($config['promotion_id']))    $query->where('promotion_id', $config['promotion_id']);
        if (! empty($config['level']))           $query->where('level', $config['level']);

        if (! empty($config['promotionInGroups'])) {
            $query->whereHas('promotion', fn ($q) => $q->where('name', $config['promotionInGroups']));
        }

        if (! empty($config['subject_id'])) {
            $query->whereHas('classeSubjects', fn ($q) =>
                $q->where('subject_id', $config['subject_id'])->where('is_active', true)->whereNull('ended_at')
            );
        }

        return $query;
    }

    protected static function studentsQuery(Classe $classe, array $config)
    {
        $query = Student::whereHas('yearlyClasseStudents', fn ($q) =>
            $q->where('classe_id', $classe->id)
              ->where('school_year_id', $classe->school_year_id)
              ->where('is_active', true)
        );

        match ($config['leavesConfig'] ?? 'onlyActives') {
            'onlyLeaves' => $query->whereHas('yearlyStudentsLeaves', fn ($q) =>
                $q->where('school_year_id', $classe->school_year_id)->where('classe_id', $classe->id)->whereNull('ended_at')
            ),
            'onlyActives' => $query->whereDoesntHave('yearlyStudentsLeaves', fn ($q) =>
                $q->where('school_year_id', $classe->school_year_id)->where('classe_id', $classe->id)->whereNull('ended_at')
            ),
            'withLeaves' => $query->where('is_active', true),
			default => $query->whereHas('yearlyStudentsLeaves', fn ($q) =>
                $q->where('school_year_id', $classe->school_year_id)
                  ->where('classe_id', $classe->id)
                  ->whereNull('ended_at'))
        };

        if (! empty($config['gender'])) {
            $g = $config['gender'];
            $query->whereIn('gender', [$g, Str::lower($g), Str::upper($g)]);
        }

        return $query->orderBy('name')->orderBy('prenames');
    }

    protected static function subjectsForClasse(Classe $classe, ?int $subjectId, int $schoolYearId): Collection
    {
        $query = ClasseSubjectOfSchoolYear::with('subject')
            ->where('classe_id', $classe->id)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at');

        if ($subjectId) $query->where('subject_id', $subjectId);

        return $query->get(['id', 'classe_id', 'subject_id', 'coefficient'])->filter(fn ($cs) => $cs->subject !== null);
    }

    /**
     * Détermine la clé et le libellé de groupe pour une classe donnée,
     * selon groupedBy. Deux classes différentes peuvent partager le même
     * groupe (ex: deux classes de la même filière).
     */
    protected static function groupKeyAndLabel(Classe $classe, string $groupedBy): array
    {
        return match ($groupedBy) {
            'filiar_id' => [
                'key'    => $classe->filiar_id ? 'filiar-' . $classe->filiar_id : 'filiar-none',
                'label'  => $classe->filiar ? 'Filière ' . ($classe->filiar->code ?: $classe->filiar->name) : 'Sans filière',
                'sortBy' => $classe->filiar?->name ?? '',
            ],
            'serial_id' => [
                'key'    => $classe->serial_id ? 'serial-' . $classe->serial_id : 'serial-none',
                'label'  => $classe->serial ? 'Série ' . ($classe->serial->code ?: $classe->serial->name) : 'Sans série',
                'sortBy' => $classe->serial?->name ?? '',
            ],
            'promotion_id' => [
                'key'    => $classe->promotion_id ? 'promo-' . $classe->promotion_id : 'promo-none',
                'label'  => $classe->promotion ? 'Promotion ' . ($classe->promotion->code ?: $classe->promotion->name) : 'Sans promotion',
                'sortBy' => $classe->promotion?->name ?? '',
            ],
            'promotionInGroups' => [
                'key'    => 'promogroup-' . ($classe->promotion?->name ?? 'none'),
                'label'  => 'Promotion ' . ($classe->promotion?->name ?? 'Sans promotion'),
                'sortBy' => $classe->promotion?->name ?? '',
            ],
            default => [ // 'classe_id'
                'key'    => 'classe-' . $classe->id,
                'label'  => 'Classe de ' . ($classe->code ?: $classe->name),
                'sortBy' => $classe->name ?? '',
            ],
        };
    }

    public static function getFormattedRows(
        array $config,
        int $schoolYearId,
        int $period,
        array $tableColumns,
        string $devoirsType,
        ?int $subjectId,
        string $targeted,   // 'best' | 'worst'
        int $limit,
        string $groupedBy
    ): array {
        $devoirColumns = array_keys(MarkPrintQuery::devoirColumns($devoirsType));
        $cacheService = app(ClasseSubjectMarksCacheService::class);

        $bucket = []; // group_key => ['label' => ..., 'sortBy' => ..., 'students' => []]

        foreach (self::classesQuery($config, $schoolYearId)->cursor() as $classe) {
            $subjects = self::subjectsForClasse($classe, $subjectId, $schoolYearId);

            if ($subjects->isEmpty()) {
                unset($classe);
                continue;
            }

            $group = self::groupKeyAndLabel($classe, $groupedBy);
            $bucket[$group['key']] ??= ['label' => $group['label'], 'sortBy' => $group['sortBy'], 'students' => []];

            $classeLabel = $classe->code ?: $classe->name;

            foreach (self::studentsQuery($classe, $config)->cursor() as $student) {

                if ($subjectId) {
                    $cs = $subjects->first();

                    $coef = $classe->getCoefValueOfSubject($subjectId);

                    $marksData = $cacheService->get($classe->id, $cs->subject_id, $period, $schoolYearId);
                    $studentMarks = $marksData[$student->id] ?? [];

                    $computed = MarkPrintQuery::computeSubjectMoy($studentMarks, $devoirColumns, (float) $coef);
                    $average = $computed['moy'];
                } else {

                    $sumMoyCoef = 0.0; 
                    $sumCoef = 0.0; 
                    $hasAnyMark = false;

                    foreach ($subjects as $cs) {
                        
                        $coef = $classe->getCoefValueOfSubject($cs->subject_id);

                        $marksData = $cacheService->get($classe->id, $cs->subject_id, $period, $schoolYearId);
                        $studentMarks = $marksData[$student->id] ?? [];

                        $computed = MarkPrintQuery::computeSubjectMoy($studentMarks, $devoirColumns, (float) $coef);

                        if (! is_null($computed['moyCoef'])) {
                            $sumMoyCoef += $computed['moyCoef'];
                            $sumCoef += (float) $coef;
                            $hasAnyMark = true;
                        }
                    }

                    $average = ($hasAnyMark && $sumCoef > 0) ? round($sumMoyCoef / $sumCoef, 2) : null;
                }

                $bucket[$group['key']]['students'][] = [
                    'student'     => $student,
                    'classeLabel' => $classeLabel,
                    'average'     => $average,
                ];

                unset($student);
            }

            unset($classe);
        }

        // ─── Tri des groupes (alphabétique), puis top/flop + rang par groupe ──
        uasort($bucket, fn ($a, $b) => strcmp($a['sortBy'], $b['sortBy']));

        $rows = [];
        $globalIndex = 0;

        foreach ($bucket as $group) {
            $students = collect($group['students'])->filter(fn ($s) => ! is_null($s['average']));

            $sorted = $targeted === 'worst'
                ? $students->sortBy('average')->values()
                : $students->sortByDesc('average')->values();

            $limited = $sorted->take($limit);

            foreach ($limited as $rank => $entry) {
                $globalIndex++;

                $context = [
                    'classeLabel' => $entry['classeLabel'],
                    'average'     => $entry['average'],
                    'rang'        => $rank + 1,
                ];

                $rows[] = [
                    'index'      => $globalIndex,
                    'groupLabel' => $group['label'],
                    'groupStart' => $rank === 0, // signale une rupture de groupe pour la vue
                    'cells'      => collect($tableColumns)
                        ->mapWithKeys(fn (array $col) => [
                            $col['key'] => MarksPrintFormatter::getData($entry['student'], $col, $context),
                        ])
                        ->toArray(),
                ];
            }
        }

        return $rows;
    }

    public static function resolveDocTitle(array $config, ?int $subjectId, string $targeted, int $limit, string $groupedBy): string
    {
        $label = $targeted === 'worst' ? 'les plus faibles' : 'meilleurs';

        $doc_title = "Classement des {$limit} {$label} apprenants";

        if ($subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) $doc_title .= " en {$subject->name}";
        } else {
            $doc_title .= " (moyenne semestrielle)";
        }

        $groupLabel = match ($groupedBy) {
            'filiar_id'         => 'par filière',
            'serial_id'         => 'par série',
            'promotion_id'      => 'par promotion',
            'promotionInGroups' => 'par promotion',
            default             => 'par classe',
        };

        $doc_title .= " {$groupLabel}";

        if (! empty($config['gender'])) {
            $doc_title .= $config['gender'] === 'F' ? ' (filles)' : ' (garçons)';
        }

        return $doc_title;
    }
}