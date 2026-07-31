<?php

namespace App\Services\MarksServices;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Student;
use App\Services\MarksServices\MarksDiagnosticFormatter;

class MarkDiagnosticQuery
{
    protected const ALL_MARK_TYPES = ['interro1', 'interro2', 'interro3', 'interro4', 'devoir1', 'devoir2', 'compo'];

    protected static function assignmentsQuery(array $config, int $schoolYearId)
    {
        $query = ClasseSubjectOfSchoolYear::with(['teacher.user', 'classe.filiar', 'classe.serial', 'classe.promotion', 'subject'])
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at');

        if (! empty($config['classe_id']))    $query->where('classe_id', $config['classe_id']);
        if (! empty($config['subject_id']))   $query->where('subject_id', $config['subject_id']);

        if (! empty($config['filiar_id']) || ! empty($config['serial_id']) || ! empty($config['promotion_id']) || ! empty($config['promotionInGroups'])) {
            $query->whereHas('classe', function ($q) use ($config) {
                if (! empty($config['filiar_id']))       $q->where('filiar_id', $config['filiar_id']);
                if (! empty($config['serial_id']))       $q->where('serial_id', $config['serial_id']);
                if (! empty($config['promotion_id']))    $q->where('promotion_id', $config['promotion_id']);
                if (! empty($config['promotionInGroups'])) {
                    $q->whereHas('promotion', fn ($qp) => $qp->where('name', $config['promotionInGroups']));
                }
            });
        }

        return $query;
    }

    public static function count(array $config, int $schoolYearId): int
    {
        return self::assignmentsQuery($config, $schoolYearId)->count();
    }

    /**
     * Élèves actifs de la classe (les abandons ne comptent pas comme "sans notes" à corriger).
     */
    protected static function activeStudentIds(int $classeId, int $schoolYearId): array
    {
        return Student::whereHas('yearlyClasseStudents', fn ($q) =>
            $q->where('classe_id', $classeId)->where('school_year_id', $schoolYearId)->where('is_active', true)
        )
        ->whereDoesntHave('yearlyStudentsLeaves', fn ($q) =>
            $q->where('school_year_id', $schoolYearId)->where('classe_id', $classeId)->whereNull('ended_at')
        )
        ->pluck('id', 'id')
        ->toArray();
    }

	// MarkDiagnosticQuery
	protected static function normalizeMarkTypes(array $checkedTypes): array
	{
		$interroLevel = 0;

		foreach (['interro4', 'interro3', 'interro2', 'interro1'] as $i => $type) {
			if (in_array($type, $checkedTypes, true)) {
				$interroLevel = 4 - $i;
				break;
			}
		}

		$normalized = $checkedTypes;

		for ($n = 1; $n <= $interroLevel; $n++) {
			$normalized[] = "interro{$n}";
		}

		return array_values(array_unique($normalized));
	}

    public static function getFormattedRows(
		array $config,
		int $schoolYearId,
		int $period,
		array $tableColumns,
		array $checkedMarkTypes,
		string $statusFilter
	): array {
		$checkedMarkTypes = self::normalizeMarkTypes($checkedMarkTypes);

		$cacheService = app(ClasseSubjectMarksCacheService::class);
		$needsList = collect($tableColumns)->pluck('key')->contains('students_without_list');
		$needsCount = $needsList || collect($tableColumns)->pluck('key')->contains('students_without_count');

		$rows = [];
		$index = 0;
		$studentsCache = [];

		foreach (self::assignmentsQuery($config, $schoolYearId)->cursor() as $assignment) {
			if (! $assignment->teacher || ! $assignment->classe || ! $assignment->subject) {
				unset($assignment);
				continue;
			}

			$classeId = $assignment->classe_id;
			$studentsCache[$classeId] ??= self::activeStudentIds($classeId, $schoolYearId);
			$studentIds = $studentsCache[$classeId];
			$totalStudents = count($studentIds);

			if ($totalStudents === 0) {
				unset($assignment);
				continue;
			}

			$marksData = $cacheService->get($classeId, $assignment->subject_id, $period, $schoolYearId);

			// ─── Complétude par type : % d'élèves ayant une valeur pour CE type ──
			$completionByType = [];
			foreach ($checkedMarkTypes as $type) {
				$count = 0;
				foreach ($studentIds as $studentId) {
					if (isset($marksData[$studentId][$type]['value'])) $count++;
				}
				$completionByType[$type] = $count / $totalStudents;
			}

			// Statut global : TOUS les types cochés doivent atteindre le seuil de 95%
			$hasMarks = ! empty($completionByType) && min($completionByType) >= 0.95;

			if ($statusFilter === 'hasMarks' && ! $hasMarks) { unset($assignment); continue; }
			if ($statusFilter === 'hasntMarks' && $hasMarks) { unset($assignment); continue; }

			// ─── Liste des élèves incomplets (manquant au moins un type coché) ──
			$missingStudentIds = [];
			if ($needsCount) {
				foreach ($studentIds as $studentId) {
					foreach ($checkedMarkTypes as $type) {
						if (! isset($marksData[$studentId][$type]['value'])) {
							$missingStudentIds[] = $studentId;
							break;
						}
					}
				}
			}

			$index++;

			$context = [
				'classeLabel'  => $assignment->classe->code ?: $assignment->classe->name,
				'subjectLabel' => $assignment->subject->code ?: $assignment->subject->name,
				'checkedTypes' => self::markTypesLabels($checkedMarkTypes),
				'hasMarks'     => $hasMarks,
				'missingCount' => $needsCount ? count($missingStudentIds) : null,
				'missingList'  => $needsList
					? Student::whereIn('id', $missingStudentIds)->get()->map(fn ($s) => $s->getFullName())->implode(', ')
					: null,
			];

			$rows[] = [
				'index' => $index,
				'cells' => collect($tableColumns)
					->mapWithKeys(fn (array $col) => [
						$col['key'] => MarksDiagnosticFormatter::getData($assignment->teacher, $col, $context),
					])
					->toArray(),
			];

			unset($assignment);
		}

		return $rows;
	}

    protected static function markTypesLabels(array $markTypes): string
    {
        $labels = [
            'interro1' => 'Int.1', 'interro2' => 'Int.2', 'interro3' => 'Int.3', 'interro4' => 'Int.4',
            'devoir1' => 'Devoir 1', 'devoir2' => 'Devoir 2', 'compo' => 'Composition',
        ];

        return collect($markTypes)->map(fn ($t) => $labels[$t] ?? $t)->implode(', ');
    }

    public static function resolveDocTitle(array $config, int $period, ?\App\Models\SchoolYear $schoolYear, string $statusFilter): string
    {
        $statusLabel = match ($statusFilter) {
            'hasMarks'   => 'ayant déjà des notes saisies',
            'hasntMarks' => "n'ayant pas encore de notes saisies",
            default      => '',
        };

        $doc_title = "Diagnostic des notes — Enseignants {$statusLabel}";

        if ($schoolYear) $doc_title .= " - {$schoolYear->periodLabel()} {$period}";

        if (! empty($config['subject_id'])) {
            $subject = \App\Models\Subject::find($config['subject_id']);
            if ($subject) $doc_title .= " en {$subject->name}";
        }

        if (! empty($config['classe_id'])) {
            $classe = Classe::find($config['classe_id']);
            if ($classe) $doc_title .= " de la classe {$classe->name}";
        }

        if (! empty($config['filiar_id'])) {
            $filiar = \App\Models\Filiar::find($config['filiar_id']);
            if ($filiar) $doc_title .= " de la filière {$filiar->name}";
        }

        if (! empty($config['promotion_id'])) {
            $promo = \App\Models\Promotion::find($config['promotion_id']);
            if ($promo) $doc_title .= " de la promotion {$promo->name}";
        }

        return $doc_title;
    }
}