<?php

namespace App\Services\BulletinsServices;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Student;
use App\Models\YearlyClasseStudent;
use App\Services\ClassesServices\ClasseEffectifsService;
use App\Services\ClassesServices\ClasseYearlyAveragesCacheService;
use App\Services\MarksServices\ClasseAveragesCacheService;
use App\Services\MarksServices\ClasseSubjectMarksCacheService;
use App\Services\MarksServices\MarkPrintQuery;

class BulletinPrintQuery
{
    public static function classesQuery(array $config, int $schoolYearId)
    {
        $query = Classe::where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->with(['filiar', 'serial', 'promotion', 'principal.user']);

        if (! empty($config['classe_id']))       $query->where('id', $config['classe_id']);
        if (! empty($config['filiar_id']))       $query->where('filiar_id', $config['filiar_id']);
        if (! empty($config['serial_id']))       $query->where('serial_id', $config['serial_id']);
        if (! empty($config['promotion_id']))    $query->where('promotion_id', $config['promotion_id']);
        if (! empty($config['level']))           $query->where('level', $config['level']);

        if (! empty($config['promotionInGroups'])) {
            $query->whereHas('promotion', fn ($q) => $q->where('name', $config['promotionInGroups']));
        }

        return $query->orderBy('name');
    }

    public static function count(array $config, int $schoolYearId): int
    {
        $total = 0;

        foreach (self::classesQuery($config, $schoolYearId)->cursor() as $classe) {
            $total += self::studentsQuery($classe, $config)->count();
            unset($classe);
        }

        return $total;
    }

    protected static function markColumns(string $devoirsType): array
    {
        return array_merge(
            ['interro1', 'interro2', 'interro3', 'interro4'],
            array_keys(MarkPrintQuery::devoirColumns($devoirsType))
        );
    }

    /**
     * Détail des matières pour UN élève — reproduction fidèle de
     * BulletinComponent::subjectsDetail(), mais réutilisable hors contexte Livewire.
     */
    protected static function subjectsDetailForStudent(Classe $classe, Student $student, int $period, int $schoolYearId, array $markColumns): array
    {
        $marksService = app(ClasseSubjectMarksCacheService::class);

        $classeSubjects = ClasseSubjectOfSchoolYear::with(['teacher.user', 'subject'])
            ->where('classe_id', $classe->id)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->get();

        return $classeSubjects->map(function (ClasseSubjectOfSchoolYear $cs) use ($marksService, $classe, $student, $period, $schoolYearId, $markColumns) {

            $data = $marksService->forStudent(
                $classe->id, $cs->subject_id, $student->id, $period, $schoolYearId
            ) ?? ['marks' => [], 'moy_interro' => null, 'moy' => null, 'moy_coef' => null, 'rank' => null, 'total' => 0, 'coefficient' => null, 'mention' => null];

            return [
                'subjectName'  => $cs->subject->name ?? '—',
                'teacherName'  => $cs->teacher?->getFullName() ?? '—',
                'coefficient'  => $data['coefficient'],
                'marks'        => collect($markColumns)->mapWithKeys(fn ($t) => [$t => $data['marks'][$t]['value'] ?? null])->all(),
                'moy_interro'  => $data['moy_interro'],
                'moy'          => $data['moy'],
                'moy_coef'     => $data['moy_coef'],
                'rank'         => $data['rank'],
                'total'        => $data['total'],
                'mention'      => $data['mention'],
            ];
        })->all();
    }

    /**
     * Génère la donnée complète d'un bulletin pour chaque élève du scope,
     * classe par classe. Retourne un array de "bulletins" prêts pour la vue.
     */
    public static function getBulletinsData(array $config, int $schoolYearId, int $period, \App\Models\SchoolYear $schoolYear): array
    {
        $devoirsType = tenant()->devoirs_type ?? 'devoir1-devoir2';
        $markColumns = self::markColumns($devoirsType);
        $devoirColumns = MarkPrintQuery::devoirColumns($devoirsType);

        $lastPeriodIndex = collect($schoolYear->getPeriods())->pluck('index')->max();
        $isLastPeriod = $period === $lastPeriodIndex;

        $bulletins = [];

        foreach (self::classesQuery($config, $schoolYearId)->cursor() as $classe) {

            $effectifs = app(ClasseEffectifsService::class)->getEffectifs($classe->id);

            $yearlyClasseData = $isLastPeriod
                ? app(ClasseYearlyAveragesCacheService::class)->get($classe->id, $schoolYearId)
                : null;

            foreach (self::studentsQuery($classe, $config)->cursor() as $student) {

                $subjectsDetail = self::subjectsDetailForStudent($classe, $student, $period, $schoolYearId, $markColumns);

                $termAverage = app(ClasseAveragesCacheService::class)->forStudent(
                    $classe->id, $student->id, $period, $schoolYearId
                );

                $yearlyAverage = $isLastPeriod
                    ? app(ClasseYearlyAveragesCacheService::class)->forStudent($classe->id, $student->id, $schoolYearId)
                    : null;

                // Récap compact : uniquement moyenne + rang des PÉRIODES PRÉCÉDENTES
                $previousPeriodsRecap = [];
                if ($isLastPeriod && $yearlyAverage && ! empty($yearlyAverage['periods'])) {
                    foreach ($yearlyAverage['periods'] as $p => $entry) {
                        if ((int) $p < $period) {
                            $previousPeriodsRecap[$p] = [
                                'moyenne' => $entry['moyenne'] ?? null,
                                'rank'    => $entry['rank'] ?? null,
                                'total'   => $entry['total'] ?? null,
                            ];
                        }
                    }
                }

                $bulletins[] = [
                    'student'              => $student,
                    'classe'               => $classe,
                    'effectifs'            => $effectifs,
                    'subjectsDetail'       => $subjectsDetail,
                    'devoirColumns'        => $devoirColumns,
                    'termAverage'          => $termAverage,
                    'isLastPeriod'         => $isLastPeriod,
                    'yearlyAverage'        => $yearlyAverage,
                    'yearlyClasseData'     => $yearlyClasseData,
                    'previousPeriodsRecap' => $previousPeriodsRecap,
                ];

                unset($student);
            }

            unset($classe);
        }

        return $bulletins;
    }

    public static function resolveDocTitle(array $config, int $period, ?\App\Models\SchoolYear $schoolYear): string
    {
        if (! empty($config['student_id'])) {
            $student = Student::find($config['student_id']);
            $doc_title = $student ? "Bulletin de {$student->getFullName()}" : "Bulletin";

            if ($schoolYear) $doc_title .= " - {$schoolYear->periodLabel()} {$period}";

            return $doc_title;
        }

        $doc_title = "Bulletins de notes";

        if ($schoolYear) $doc_title .= " - {$schoolYear->periodLabel()} {$period}";

        if (! empty($config['classe_id'])) {
            $classe = Classe::find($config['classe_id']);
            if ($classe) $doc_title .= " de la classe {$classe->name}";
        }

        if (! empty($config['filiar_id'])) {
            $filiar = \App\Models\Filiar::find($config['filiar_id']);
            if ($filiar) $doc_title .= " de la filière {$filiar->name}";
        }

        if (! empty($config['serial_id'])) {
            $serial = \App\Models\Serial::find($config['serial_id']);
            if ($serial) $doc_title .= " de la série {$serial->name}";
        }

        if (! empty($config['promotion_id'])) {
            $promo = \App\Models\Promotion::find($config['promotion_id']);
            if ($promo) $doc_title .= " de la promotion {$promo->name}";
        }

        if (! empty($config['promotionInGroups'])) {
            $doc_title .= " de la promotion {$config['promotionInGroups']}";
        }

        return $doc_title;
    }

    protected static function studentsQuery(Classe $classe, array $config)
    {
        $query = Student::whereHas('yearlyClasseStudents', fn ($q) =>
            $q->where('classe_id', $classe->id)
              ->where('school_year_id', $classe->school_year_id)
              ->where('is_active', true)
        );

        if (! empty($config['student_id'])) {
            
            $query->where('id', $config['student_id']);

            return $query->orderBy('name')->orderBy('prenames');
        }

        match ($config['leavesConfig'] ?? 'onlyActives') {
            'onlyLeaves' => $query->whereHas('yearlyStudentsLeaves', fn ($q) =>
                $q->where('school_year_id', $classe->school_year_id)->where('classe_id', $classe->id)->whereNull('ended_at')
            ),
            'onlyActives' => $query->whereDoesntHave('yearlyStudentsLeaves', fn ($q) =>
                $q->where('school_year_id', $classe->school_year_id)->where('classe_id', $classe->id)->whereNull('ended_at')
            ),
            
			'withLeaves' => $query->whereHas('yearlyStudentsLeaves', fn ($q) =>
                $q->where('school_year_id', $classe->school_year_id)
                  ->where('classe_id', $classe->id)
                  ->whereNull('ended_at')),
            default => $query->where('is_active', true),
        };

        return $query->orderBy('name')->orderBy('prenames');
    }



    /**
     * Résout la classe active d'un élève pour l'année scolaire donnée —
     * nécessaire pour restreindre classesQuery() à cette seule classe
     * quand on génère le bulletin d'un élève précis.
     */
    public static function resolveClasseIdForStudent(int $studentId, int $schoolYearId): ?int
    {
        return YearlyClasseStudent::where('student_id', $studentId)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->value('classe_id');
    }
}