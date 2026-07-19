<?php

namespace App\Services\ClassesServices;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\YearlyClasseStudent;
use App\Models\YearlyClasseStudentsLeave;
use Illuminate\Database\Eloquent\Builder;

class ClassePrintQuery
{
    public static function build(array $config, int $schoolYearId): Builder
    {
        $query = Classe::query()
            ->where('school_year_id', $schoolYearId)
            ->with(['filiar', 'serial', 'promotion', 'principal.user', 'respo1', 'respo2']);

        if (array_key_exists('activeConfig', $config) && $config['activeConfig']) {
            match ($config['activeConfig']) {
                'onlyActive'    => $query->where('is_active', true),
                'onlyInactive'  => $query->where('is_active', false),
                default         => null,
            };
        }

        if (! empty($config['lockedConfig'])) {
            match ($config['lockedConfig']) {
                'onlyLocked'   => $query->where('is_locked', true),
                'onlyUnlocked' => $query->where('is_locked', false),
                default        => null,
            };
        }

        if (! empty($config['ppConfig'])) {
            match ($config['ppConfig']) {
                'onlyHasPP'    => $query->whereNotNull('principal_id'),
                'onlyHasntPP'  => $query->whereNull('principal_id'),
                default        => null,
            };
        }

        if (! empty($config['hasStudentsConfig'])) {
            match ($config['hasStudentsConfig']) {
                'onlyHasStudents'   => $query->whereHas('students', fn ($q) =>
                    $q->where('is_active', true)),
                'onlyHasntStudents' => $query->whereDoesntHave('students', fn ($q) =>
                    $q->where('is_active', true)),
                default => null,
            };
        }

        if (! empty($config['hasTeachersConfig'])) {
            match ($config['hasTeachersConfig']) {
                'onlyHasTeachers'   => $query->whereHas('classeSubjects', fn ($q) =>
                    $q->where('is_active', true)->whereNull('ended_at')),
                'onlyHasntTeachers' => $query->whereDoesntHave('classeSubjects', fn ($q) =>
                    $q->where('is_active', true)->whereNull('ended_at')),
                default => null,
            };
        }

        if (! empty($config['classe_id']))       $query->where('id', $config['classe_id']);
        if (! empty($config['filiar_id']))       $query->where('filiar_id', $config['filiar_id']);
        if (! empty($config['serial_id']))       $query->where('serial_id', $config['serial_id']);
        if (! empty($config['promotion_id']))    $query->where('promotion_id', $config['promotion_id']);
        if (! empty($config['level']))           $query->where('level', $config['level']);

        if (! empty($config['promotionInGroups'])) {
            $query->whereHas('promotion', fn ($q) => $q->where('name', $config['promotionInGroups']));
        }

        return $query;
    }

    public static function count(array $config, int $schoolYearId): int
    {
        return self::build($config, $schoolYearId)->count();
    }

    // ─── Maps d'agrégation — une requête groupée par métrique ──

    public static function studentsCountMap(int $schoolYearId): array
    {
        return YearlyClasseStudent::where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->get(['id', 'classe_id', 'student_id'])
            ->groupBy('classe_id')
            ->map(fn ($group) => $group->count())
            ->toArray();
    }

    public static function studentsCountByGenderMap(int $schoolYearId): array
    {
        return YearlyClasseStudent::with('student:id,gender')
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->get(['id', 'classe_id', 'student_id'])
            ->groupBy('classe_id')
            ->map(function ($group) {
                $genders = $group->pluck('student.gender')->filter();

                return [
                    'garcons' => $genders->filter(fn ($g) => strtoupper(substr($g, 0, 1)) === 'M')->count(),
                    'filles'  => $genders->filter(fn ($g) => strtoupper(substr($g, 0, 1)) === 'F')->count(),
                ];
            })
            ->toArray();
    }

    public static function teachersCountMap(int $schoolYearId): array
    {
        return ClasseSubjectOfSchoolYear::where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->get(['id', 'classe_id', 'teacher_id'])
            ->groupBy('classe_id')
            ->map(fn ($group) => $group->pluck('teacher_id')->unique()->count())
            ->toArray();
    }

    public static function leavesCountMap(int $schoolYearId): array
    {
        return YearlyClasseStudentsLeave::where('school_year_id', $schoolYearId)
            ->get(['id', 'classe_id'])
            ->groupBy('classe_id')
            ->map(fn ($group) => $group->count())
            ->toArray();
    }

    public static function formatRows($query, array $tableColumns, int $schoolYearId): array
    {
        $context = [
            'studentsCount'  => self::studentsCountMap($schoolYearId),
            'studentsByGender' => self::studentsCountByGenderMap($schoolYearId),
            'teachersCount'  => self::teachersCountMap($schoolYearId),
            'leavesCount'    => self::leavesCountMap($schoolYearId),
        ];

        $rows = [];

        foreach ($query->cursor() as $classe) {
            $byGender = $context['studentsByGender'][$classe->id] ?? ['garcons' => 0, 'filles' => 0];

            $rows[] = [
                'index'  => count($rows) + 1,
                'cells'  => collect($tableColumns)
                    ->mapWithKeys(fn (array $col) => [
                        $col['key'] => \App\Livewire\Tenants\Classes\ClassesPrintableListComponent::getData(
                            $classe,
                            $col,
                            [
                                'studentsTotal'   => $context['studentsCount'][$classe->id] ?? 0,
                                'studentsGarcons' => $byGender['garcons'],
                                'studentsFilles'  => $byGender['filles'],
                                'teachersCount'   => $context['teachersCount'][$classe->id] ?? 0,
                                'leavesCount'     => $context['leavesCount'][$classe->id] ?? 0,
                                // 'bestStudent', 'worstStudent', 'bestBoy', 'bestGirl' — en attente de précision
                            ]
                        ),
                    ])
                    ->toArray(),
            ];

            unset($classe);
        }

        return $rows;
    }

    public static function getFormattedRows(array $config, int $schoolYearId, array $tableColumns): array
    {
        $query = self::build($config, $schoolYearId);

        return self::formatRows($query, $tableColumns, $schoolYearId);
    }

    public static function resolveDocTitle(array $config, ?int $school_year_id = null): string
    {
        $doc_title = 'Liste des classes ';

        if (! $config) return $doc_title;

        if (isset($config['activeConfig'])) {
            match ($config['activeConfig']) {
                'onlyActive'   => $doc_title .= '',
                'onlyInactive' => $doc_title .= ' inactives ',
                default        => null,
            };
        }

        if (isset($config['ppConfig'])) {
            match ($config['ppConfig']) {
                'onlyHasPP'   => $doc_title .= ' ayant un Professeur Principal ',
                'onlyHasntPP' => $doc_title .= ' sans Professeur Principal ',
                default       => null,
            };
        }

        if (isset($config['hasStudentsConfig'])) {
            match ($config['hasStudentsConfig']) {
                'onlyHasStudents'   => $doc_title .= '',
                'onlyHasntStudents' => $doc_title .= ' sans apprenant ',
                default             => null,
            };
        }

        if (isset($config['hasTeachersConfig'])) {
            match ($config['hasTeachersConfig']) {
                'onlyHasTeachers'   => $doc_title .= '',
                'onlyHasntTeachers' => $doc_title .= ' sans enseignant ',
                default             => null,
            };
        }

        if (isset($config['level'])) $doc_title .= " de niveau {$config['level']}";

        if (isset($config['promotion_id'])) {
            $promo = \App\Models\Promotion::firstWhere('id', $config['promotion_id']);
            if ($promo) $doc_title .= " de la promotion {$promo->name}";
        }

        if (isset($config['promotionInGroups'])) $doc_title .= " de la promotion {$config['promotionInGroups']}";

        if (isset($config['filiar_id'])) {
            $filiar = \App\Models\Filiar::firstWhere('id', $config['filiar_id']);
            if ($filiar) $doc_title .= " de la filière {$filiar->name}";
        }

        if (isset($config['serial_id'])) {
            $serial = \App\Models\Serial::firstWhere('id', $config['serial_id']);
            if ($serial) $doc_title .= " de la série {$serial->name}";
        }

        return $doc_title;
    }
}