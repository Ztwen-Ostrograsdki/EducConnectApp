<?php

namespace App\Services\BulletinsServices;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\ModuleClasseValidation;
use App\Models\Student;
use App\Services\MarksServices\ClasseSubjectMarksCacheService;
use Illuminate\Support\Collection;

/**
 * Bulletins des classes "nouveaux métiers" (classes.is_new_system = true).
 *
 * - Aucune note affichée : par module, uniquement "Validé" / "Non validé".
 * - Seuil du module = ModuleClasseValidation (année + classe + module),
 *   sinon config('metier.default_module_limit').
 * - Décision finale (dernière période uniquement) : admis si le % de modules
 *   validés >= config('metier.percentage_to_be_declared_successed').
 */
class NewSystemBulletinService
{
    public const VALIDATED     = 'validated';
    public const NOT_VALIDATED = 'not_validated';
    public const NOT_GRADED    = 'not_graded';

    public static function defaultLimit(): float
    {
        return (float) config('metier.default_module_limit', 10);
    }

    public static function requiredPercentage(): float
    {
        return (float) config('metier.percentage_to_be_declared_successed', 50);
    }

    /**
     * Seuils [module_id => seuil] d'une classe pour son année scolaire.
     * À appeler UNE fois par classe (pas par élève).
     */
    public static function thresholdsForClasse(Classe $classe): array
    {
        return ModuleClasseValidation::forSchoolYear($classe->school_year_id)
            ->forClasse($classe->id)
            ->pluck('seuil', 'module_id')
            ->map(fn ($v) => (float) $v)
            ->all();
    }

    /**
     * Modules actifs de la classe (matières is_module = true).
     * À appeler UNE fois par classe.
     */
    public static function modulesOfClasse(Classe $classe, int $schoolYearId): Collection
    {
        return ClasseSubjectOfSchoolYear::with(['teacher.user', 'subject'])
            ->where('classe_id', $classe->id)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->whereHas('subject', fn ($q) => $q->where('is_module', true))
            ->get()
            ->sortBy(fn ($cs) => $cs->subject->name ?? '')
            ->values();
    }

    /**
     * Données complètes du bulletin d'un élève d'une classe nouveau système.
     */
    public static function build(
        Classe $classe,
        Student $student,
        int $period,
        int $schoolYearId,
        bool $isLastPeriod,
        Collection $modules,
        array $thresholds,
        array $effectifs = []
    ): array {
        $marksService = app(ClasseSubjectMarksCacheService::class);
        $default      = self::defaultLimit();

        $rows      = [];
        $validated = 0;

        foreach ($modules as $cs) {
            $data = $marksService->forStudent(
                $classe->id, $cs->subject_id, $student->id, $period, $schoolYearId
            );

            $moy   = $data['moy'] ?? null;
            $seuil = $thresholds[$cs->subject_id] ?? $default;

            if ($moy === null) {
                $status = self::NOT_GRADED;
            } elseif ((float) $moy >= $seuil) {
                $status = self::VALIDATED;
                $validated++;
            } else {
                $status = self::NOT_VALIDATED;
            }

            $rows[] = [
                'moduleName' => $cs->subject->name ?? '—',
                'teacherName' => $cs->teacher?->getFullName() ?? '—',
                'status'     => $status,
            ];
        }

        $total      = count($rows);
        $percentage = $total > 0 ? round(($validated / $total) * 100, 2) : 0.0;

        // Décision uniquement à la dernière période
        $decision = null;
        if ($isLastPeriod) {
            $required = self::requiredPercentage();

            $decision = [
                'admitted'   => $total > 0 && $percentage >= $required,
                'required'   => $required,
            ];
        }

        return [
            'type'         => 'new_system',
            'student'      => $student,
            'classe'       => $classe,
            'effectifs'    => $effectifs,
            'isLastPeriod' => $isLastPeriod,
			'termAverage'  => $moy,
            'modules'      => $rows,
            'summary'      => [
                'validated'  => $validated,
                'total'      => $total,
                'percentage' => $percentage,
            ],
            'decision'     => $decision,
        ];
    }
}