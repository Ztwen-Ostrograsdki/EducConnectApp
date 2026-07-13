<?php

namespace App\Services\StudentsServices;

use App\Livewire\Tenants\Students\StudentsPrintableListComponent;
use App\Models\Student;
use App\Models\YearlyClasseStudent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class StudentPrintQuery
{
    public static function build(array $config, int $schoolYearId): Builder
    {
        $query = Student::query()->select('students.*');

        if (! empty($config['trashedConfig'])) {
            $query->{$config['trashedConfig']}();
        }

        if (! empty($config['leavesConfig'])) {
            match ($config['leavesConfig']) {
                'onlyLeaves'  => $query->whereHas('yearlyStudentsLeaves', fn ($q) => $q->where('school_year_id', $schoolYearId)),
                'onlyActives' => $query->whereDoesntHave('yearlyStudentsLeaves', fn ($q) => $q->where('school_year_id', $schoolYearId)),
                default       => null,
            };
        }

        if (! empty($config['hasClasseConfig'])) {
            match ($config['hasClasseConfig']) {
                'onlyHasClasse'   => $query->whereHas('classes', fn ($q) => $q->where('school_year_id', $schoolYearId)->where('is_active', true)),
                'onlyHasntClasse' => $query->whereDoesntHave('classes', fn ($q) => $q->where('school_year_id', $schoolYearId)),
                default           => null,
            };
        }

        if (! empty($config['city'])) {
            $query->where('city', $config['city']);
        }

        if (! empty($config['gender'])) {
            $query->whereIn('gender', [$config['gender'], Str::lower($config['gender']), Str::upper($config['gender'])]);
        }

        if (! empty($config['department'])) {
            $query->where('department', $config['department']);
        }

        if (! empty($config['classe_id'])) {
            $query->whereHas('classes', fn ($q) =>
                $q->where('is_active', true)->where('classe_id', $config['classe_id'])->where('school_year_id', $schoolYearId)
            );
        }

        if (! empty($config['promotion_id'])) {
            $query->whereHas('classes', fn ($q) =>
                $q->where('is_active', true)->where('school_year_id', $schoolYearId)
                  ->whereHas('classe', fn ($qr) =>
                      $qr->where('promotion_id', $config['promotion_id'])->where('is_active', true)->where('school_year_id', $schoolYearId)
                  )
            );
        }

        if (! empty($config['promotionInGroups'])) {
            $query->whereHas('classes', fn ($q) =>
                $q->where('is_active', true)->where('school_year_id', $schoolYearId)
                  ->whereHas('classe', fn ($qr0) =>
                      $qr0->whereHas('promotion', fn ($qr) =>
                          $qr->where('name', $config['promotionInGroups'])->where('is_active', true)->where('school_year_id', $schoolYearId)
                      )
                  )
            );
        }

        if (! empty($config['filiar_id'])) {
            $query->whereHas('classes', fn ($q) =>
                $q->where('is_active', true)->where('school_year_id', $schoolYearId)
                  ->whereHas('classe', fn ($qr) =>
                      $qr->where('filiar_id', $config['filiar_id'])->where('is_active', true)->where('school_year_id', $schoolYearId)
                  )
            );
        }

        if (! empty($config['serial_id'])) {
            $query->whereHas('classes', fn ($q) =>
                $q->where('is_active', true)->where('school_year_id', $schoolYearId)
                  ->whereHas('classe', fn ($qr) =>
                      $qr->where('serial_id', $config['serial_id'])->where('is_active', true)->where('school_year_id', $schoolYearId)
                  )
            );
        }

        return $query->orderBy('name')->orderBy('prenames');
    }

    public static function get(array $config, int $schoolYearId): Collection
    {
        return self::build($config, $schoolYearId)->get();
    }




    public static function classeLabelsMap(int $schoolYearId): array
    {
        return YearlyClasseStudent::with('classe')
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->get(['id', 'student_id', 'classe_id'])
            ->mapWithKeys(fn ($ycs) => [
                $ycs->student_id => $ycs->classe
                    ? ($ycs->classe->code ?: $ycs->classe->name)
                    : null,
            ])
            ->toArray();
    }

    public static function getFormattedRows(array $config, int $schoolYearId, array $tableColumns): array
    {
        $classeLabels = self::classeLabelsMap($schoolYearId);

        $query = self::build($config, $schoolYearId)
            ->select(self::resolveRequiredColumns($tableColumns));

        $rows = [];

        foreach ($query->cursor() as $student) {
            $rows[] = [
                'index'     => count($rows) + 1,
                'matricule' => $student->matricule,
                'email'     => $student->email,
                'cells'     => collect($tableColumns)
                    ->mapWithKeys(fn (array $col) => [
                        $col['key'] => StudentsPrintableListComponent::getData(
                            $student,
                            $col,
                            ['classeLabel' => $classeLabels[$student->id] ?? null]
                        ),
                    ])
                    ->toArray(),
            ];

            unset($student);
        }

        return $rows;
    }

    public static function count(array $config, int $schoolYearId): int
    {
        return self::build($config, $schoolYearId)->count();
    }

    public static function resolveRequiredColumns(array $tableColumns): array
    {
        $base = ['id', 'matricule', 'email'];

        $columnKeyToDbField = [
            'educMaster'        => 'educMaster',
            'gender'            => 'gender',
            'father_full_name'  => 'father_full_name',
            'mother_full_name'  => 'mother_full_name',
            'contacts'          => 'contacts',
            'birth_date'        => 'birth_date',
            'status'            => 'status',
            // 'observations'      => 'observations',
        ];

        $needed = collect($tableColumns)
            ->pluck('key')
            ->map(fn ($key) => $columnKeyToDbField[$key] ?? null)
            ->filter()
            ->push('name', 'prenames') // requis par getFullName()
            ->toArray();

        return array_unique(array_merge($base, $needed));
    }
}