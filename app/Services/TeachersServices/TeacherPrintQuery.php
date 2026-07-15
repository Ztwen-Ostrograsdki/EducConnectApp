<?php

namespace App\Services\TeachersServices;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\Serial;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherYearlyAccess;
use App\Models\TeacherYearlySubject;
use App\Models\YearlySubjectChief;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class TeacherPrintQuery
{
    public static function build(array $config, int $schoolYearId): Builder
    {
        $query = Teacher::query()->select('teachers.*')->with('user');

        if (! empty($config['trashedConfig'])) {
            $query->{$config['trashedConfig']}();
        }

        if (! empty($config['accessesConfig'])) {
            match ($config['accessesConfig']) {
                'onlyHasAccess'    => $query->whereHas('yearlyAccesses', fn ($q) =>
                    $q->where('school_year_id', $schoolYearId)->where('status', 'active')),
                'onlyHasntAccess'  => $query->whereDoesntHave('yearlyAccesses', fn ($q) =>
                    $q->where('school_year_id', $schoolYearId)->where('status', 'active')),
                default => null,
            };
        }

        if (! empty($config['ppConfig'])) {
            match ($config['ppConfig']) {
                'onlyPP'    => $query->whereHas('principalClasses', fn ($q) =>
                    $q->where('school_year_id', $schoolYearId)->where('is_active', true)),
                'withoutPP' => $query->whereDoesntHave('principalClasses', fn ($q) =>
                    $q->where('school_year_id', $schoolYearId)->where('is_active', true)),
                default => null,
            };
        }

        if (! empty($config['aeConfig'])) {
            match ($config['aeConfig']) {
                'onlyAE'    => $query->whereHas('subjectsChiefs', fn ($q) =>
                    $q->where('school_year_id', $schoolYearId)->where('is_active', true)),
                'withoutAE' => $query->whereDoesntHave('subjectsChiefs', fn ($q) =>
                    $q->where('school_year_id', $schoolYearId)->where('is_active', true)),
                default => null,
            };
        }

        if (! empty($config['hasClassesConfig'])) {
            match ($config['hasClassesConfig']) {
                'onlyHasClasses'   => $query->whereHas('classeSubjects', fn ($q) =>
                    $q->where('school_year_id', $schoolYearId)->where('is_active', true)->whereNull('ended_at')),
                'onlyHasntClasses' => $query->whereDoesntHave('classeSubjects', fn ($q) =>
                    $q->where('school_year_id', $schoolYearId)->where('is_active', true)->whereNull('ended_at')),
                default => null,
            };
        }

        if (! empty($config['classe_id'])) {
            $query->whereHas('classeSubjects', fn ($q) =>
                $q->where('school_year_id', $schoolYearId)->where('classe_id', $config['classe_id'])
                  ->where('is_active', true)->whereNull('ended_at')
            );
        }

        if (! empty($config['subject_id'])) {
            $query->whereHas('classeSubjects', fn ($q) =>
                $q->where('school_year_id', $schoolYearId)->where('subject_id', $config['subject_id'])
                  ->where('is_active', true)->whereNull('ended_at')
            );
        }

        if (! empty($config['filiar_id'])) {
            $query->whereHas('classeSubjects', fn ($q) =>
                $q->where('school_year_id', $schoolYearId)->where('is_active', true)->whereNull('ended_at')
                  ->whereHas('classe', fn ($qr) => $qr->where('filiar_id', $config['filiar_id'])->where('is_active', true))
            );
        }

        if (! empty($config['serial_id'])) {
            $query->whereHas('classeSubjects', fn ($q) =>
                $q->where('school_year_id', $schoolYearId)->where('is_active', true)->whereNull('ended_at')
                  ->whereHas('classe', fn ($qr) => $qr->where('serial_id', $config['serial_id'])->where('is_active', true))
            );
        }

        if (! empty($config['promotion_id'])) {
            $query->whereHas('classeSubjects', fn ($q) =>
                $q->where('school_year_id', $schoolYearId)->where('is_active', true)->whereNull('ended_at')
                  ->whereHas('classe', fn ($qr) => $qr->where('promotion_id', $config['promotion_id'])->where('is_active', true))
            );
        }

        if (! empty($config['promotionInGroups'])) {
            $query->whereHas('classeSubjects', fn ($q) =>
                $q->where('school_year_id', $schoolYearId)->where('is_active', true)->whereNull('ended_at')
                  ->whereHas('classe', fn ($qr) => $qr->whereHas('promotion', fn ($qp) =>
                      $qp->where('name', $config['promotionInGroups'])->where('is_active', true)
                  ))
            );
        }

        if (! empty($config['gender'])) {
            $query->whereHas('user', fn ($q) =>
                $q->whereIn('gender', [$config['gender'], Str::lower($config['gender']), Str::upper($config['gender'])])
            );
        }

        if (! empty($config['city'])) {
            $query->whereHas('user', fn ($q) => $q->where('city', $config['city']));
        }

        if (! empty($config['department'])) {
            $query->whereHas('user', fn ($q) => $q->where('department', $config['department']));
        }

        return $query;
    }

    public static function count(array $config, int $schoolYearId): int
    {
        return self::build($config, $schoolYearId)->count();
    }

    // ─── Maps d'agrégation — une requête groupée, pas une par enseignant ──

    public static function classesMap(int $schoolYearId): array
    {
        return ClasseSubjectOfSchoolYear::with('classe')
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->get(['id', 'teacher_id', 'classe_id'])
            ->groupBy('teacher_id')
            ->map(fn ($group) => $group->pluck('classe')->filter()
                ->map(fn ($c) => str()->upper($c->code ?: $c->name))->unique()->implode(' / '))
            ->toArray();
    }

    public static function subjectsMap(int $schoolYearId): array
    {
        return TeacherYearlySubject::with('subject')
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->get(['id', 'teacher_id', 'subject_id'])
            ->groupBy('teacher_id')
            ->map(fn ($group) => $group->pluck('subject')->filter()
                ->map(fn ($s) => str()->upper($s->code ?: $s->name))->unique()->implode(' / '))
            ->toArray();
    }

    public static function ppMap(int $schoolYearId): array
    {
        return Classe::where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->whereNotNull('principal_id')
            ->get(['id', 'principal_id', 'code', 'name'])
            ->groupBy('principal_id')
            ->map(fn ($group) => $group->map(fn ($c) => $c->code ?: $c->name)->unique()->implode(', '))
            ->toArray();
    }

    public static function aeMap(int $schoolYearId): array
    {
        return YearlySubjectChief::with('subject')
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->get(['id', 'teacher_id', 'subject_id'])
            ->groupBy('teacher_id')
            ->map(fn ($group) => $group->pluck('subject')->filter()
                ->map(fn ($s) => $s->code ?: $s->name)->unique()->implode(', '))
            ->toArray();
    }

    public static function accessStatusMap(int $schoolYearId): array
    {
        return TeacherYearlyAccess::where('school_year_id', $schoolYearId)
            ->get(['id', 'teacher_id', 'status'])
            ->groupBy('teacher_id')
            ->map(fn ($group) => $group->first()->status)
            ->toArray();
    }

    // ─── Résolution finale, curseur + maps pré-résolues ──

    public static function formatRows($query, array $tableColumns, int $schoolYearId): array
    {
        $context = [
            'classes'  => self::classesMap($schoolYearId),
            'subjects' => self::subjectsMap($schoolYearId),
            'pp'       => self::ppMap($schoolYearId),
            'ae'       => self::aeMap($schoolYearId),
            'access'   => self::accessStatusMap($schoolYearId),
        ];

        $rows = [];

        foreach ($query->cursor() as $teacher) {
            $rows[] = [
                'index'     => count($rows) + 1,
                'identifiant'=> $teacher->identifiant,
                'email'     => $teacher->email,
                'cells'     => collect($tableColumns)
                    ->mapWithKeys(fn (array $col) => [
                        $col['key'] => \App\Livewire\Tenants\Teachers\TeachersPrintableListComponent::getData(
                            $teacher,
                            $col,
                            [
                                'classesLabel'  => $context['classes'][$teacher->id] ?? null,
                                'subjectsLabel' => $context['subjects'][$teacher->id] ?? null,
                                'ppLabel'       => $context['pp'][$teacher->id] ?? null,
                                'aeLabel'       => $context['ae'][$teacher->id] ?? null,
                                'accessStatus'  => $context['access'][$teacher->id] ?? null,
                            ]
                        ),
                    ])
                    ->toArray(),
            ];

            unset($teacher);
        }

        return $rows;
    }

    public static function getFormattedRows(array $config, int $schoolYearId, array $tableColumns): array
    {
        $query = self::build($config, $schoolYearId)->with('user');

        return self::formatRows($query, $tableColumns, $schoolYearId);
    }


    public static function resolveDocTitle(array $config): string
    {
        $doc_title = 'Liste de tous les enseignants ';

        if (! $config) return $doc_title;

        if (isset($config['trashedConfig'])) {
            match ($config['trashedConfig']) {
                'onlyTrashed'    => $doc_title .= ' de la corbeille ',
                'withoutTrashed' => $doc_title .= '',
                default          => $doc_title .= '',
            };
        }

        if (isset($config['accessesConfig'])) {
            match ($config['accessesConfig']) {
                'onlyHasAccess'   => $doc_title .= ' ayant un accès ',
                'onlyHasntAccess' => $doc_title .= ' sans accès ',
                default           => null,
            };
        }

        if (isset($config['ppConfig'])) {
            match ($config['ppConfig']) {
                'onlyPP'    => $doc_title .= ' PP ',
                'withoutPP' => $doc_title .= ' non PP ',
                default     => null,
            };
        }

        if (isset($config['aeConfig'])) {
            match ($config['aeConfig']) {
                'onlyAE'    => $doc_title .= ' AE ',
                'withoutAE' => $doc_title .= ' non AE ',
                default     => null,
            };
        }

        if (isset($config['hasClassesConfig'])) {
            match ($config['hasClassesConfig']) {
                'onlyHasClasses'   => $doc_title .= '',
                'onlyHasntClasses' => $doc_title .= " sans classe ",
                default            => null,
            };
        }

        if (isset($config['city'])) $doc_title .= " de {$config['city']}";

        if (isset($config['gender'])) $doc_title .= " de sexe {$config['gender']}";

        if (isset($config['department'])) $doc_title .= " de {$config['department']}";

        if (isset($config['classe_id'])) {
            $classe = Classe::firstWhere('id', $config['classe_id']);

            if ($classe) $doc_title .= " de la classe {$classe->name} ";
        }

        if (isset($config['subject_id'])) {
            $subject = Subject::firstWhere('id', $config['subject_id']);

            if ($subject) $doc_title .= " enseignant la matière {$subject->name} ";
        }

        if (isset($config['promotion_id'])) {
            $promo = Promotion::firstWhere('id', $config['promotion_id']);

            if ($promo) $doc_title .= "de la promotion {$promo->name}";
        }

        if (isset($config['promotionInGroups'])) $doc_title .= " de la promotion {$config['promotionInGroups']}";

        if (isset($config['filiar_id'])) {
            $filiar = Filiar::firstWhere('id', $config['filiar_id']);

            if ($filiar) $doc_title .= " de la filière {$filiar->name}";
        }

        if (isset($config['serial_id'])) {
            $serial = Serial::firstWhere('id', $config['serial_id']);

            if ($serial) $doc_title .= " de la série {$serial->name}";
        }

        return $doc_title;
    }
}