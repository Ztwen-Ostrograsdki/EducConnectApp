<?php

namespace App\Services\StudentsServices;

use App\Livewire\Tenants\Students\StudentsPrintableListComponent;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\Serial;
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

        if (isset($config['trashedConfig'])) {
            $query->{$config['trashedConfig']}();
        }

        if (isset($config['leavesConfig'])) {
            match ($config['leavesConfig']) {
                'onlyLeaves'  => $query->whereHas('yearlyStudentsLeaves', fn ($q) => $q->where('school_year_id', $schoolYearId)),
                'onlyActives' => $query->whereDoesntHave('yearlyStudentsLeaves', fn ($q) => $q->where('school_year_id', $schoolYearId)),
                default       => null,
            };
        }

        if (isset($config['hasClasseConfig'])) {
            match ($config['hasClasseConfig']) {
                'onlyHasClasse'   => $query->whereHas('classes', fn ($q) => $q->where('school_year_id', $schoolYearId)->where('is_active', true)),
                'onlyHasntClasse' => $query->whereDoesntHave('classes', fn ($q) => $q->where('school_year_id', $schoolYearId)),
                default           => null,
            };
        }

        if (isset($config['city'])) {
            $query->where('city', $config['city']);
        }

        if (isset($config['gender'])) {
            $query->whereIn('gender', [$config['gender'], Str::lower($config['gender']), Str::upper($config['gender'])]);
        }

        if (isset($config['department'])) {
            $query->where('department', $config['department']);
        }

        if (isset($config['classe_id'])) {
            $query->whereHas('classes', fn ($q) =>
                $q->where('is_active', true)->where('classe_id', $config['classe_id'])->where('school_year_id', $schoolYearId)
            );
        }

        if (isset($config['promotion_id'])) {
            $query->whereHas('classes', fn ($q) =>
                $q->where('is_active', true)->where('school_year_id', $schoolYearId)
                  ->whereHas('classe', fn ($qr) =>
                      $qr->where('promotion_id', $config['promotion_id'])->where('is_active', true)->where('school_year_id', $schoolYearId)
                  )
            );
        }

        if (isset($config['promotionInGroups'])) {
            $query->whereHas('classes', fn ($q) =>
                $q->where('is_active', true)->where('school_year_id', $schoolYearId)
                  ->whereHas('classe', fn ($qr0) =>
                      $qr0->whereHas('promotion', fn ($qr) =>
                          $qr->where('name', $config['promotionInGroups'])->where('is_active', true)->where('school_year_id', $schoolYearId)
                      )
                  )
            );
        }

        if (isset($config['filiar_id'])) {
            $query->whereHas('classes', fn ($q) =>
                $q->where('is_active', true)->where('school_year_id', $schoolYearId)
                  ->whereHas('classe', fn ($qr) =>
                      $qr->where('filiar_id', $config['filiar_id'])->where('is_active', true)->where('school_year_id', $schoolYearId)
                  )
            );
        }

        if (isset($config['serial_id'])) {
            $query->whereHas('classes', fn ($q) =>
                $q->where('is_active', true)->where('school_year_id', $schoolYearId)
                  ->whereHas('classe', fn ($qr) =>
                      $qr->where('serial_id', $config['serial_id'])->where('is_active', true)->where('school_year_id', $schoolYearId)
                  )
            );
        }

        return $query->orderBy('name')->orderBy('prenames');
    }


    

    public static function resolveDocTitle(array $config, ?int $school_year_id = null) : string
    {
        $doc_title = 'Liste des apprenants ';

        if(!$config) return $doc_title;

        if (isset($config['trashedConfig'])) {

            match ($config['trashedConfig']) {
                'onlyTrashed'  => $doc_title .= ' de la corbeille ',
                'withoutTrashed' => $doc_title .= '',
                default       => $doc_title .= '',
            };

        }

        if (isset($config['leavesConfig'])) {
            match ($config['leavesConfig']) {
                'onlyLeaves'  => $doc_title .= ' ayant abandonnés ',
                'onlyActives' => '',
                default       => null,
            };
        }

        if (isset($config['hasClasseConfig'])) {
            match ($config['hasClasseConfig']) {
                'onlyHasClasse'   => $doc_title .= '',
                'onlyHasntClasse' => $doc_title .= " sans classe ",
                default           => null,
            };
        }

        if (isset($config['city'])) $doc_title .= ' de ' . $config['city'];

        if (isset($config['gender'])) $doc_title .= ' de sexe ' . $config['gender'];
            
        if (isset($config['department'])) $doc_title .= ' de ' . $config['department'];

        if (isset($config['classe_id'])) {

            $classe = Classe::firstWhere($config['classe_id']);

            if($classe) $doc_title .= ' de la ' . $classe->code ? $classe->code : $classe->name;
        }

        if (isset($config['promotion_id'])) {

            $promo = Promotion::firstWhere($config['promotion_id']);

            if($promo) $doc_title .= ' de la ' . $promo->code ? $promo->code : $promo->name;
            
        }

        if (isset($config['promotionInGroups'])) $doc_title .= ' de la promotion ' . $config['promotionInGroups'];

        if (isset($config['filiar_id'])) {

            $filiar = Filiar::firstWhere($config['filiar_id']);

            if($filiar) $doc_title .= ' de la ' . $filiar->code ? $filiar->code : $filiar->name;
            
        }

        if (isset($config['serial_id'])) {

            $serial = Serial::firstWhere($config['serial_id']);

            if($serial) $doc_title .= ' de la série ' . $serial->code ? $serial->code : $serial->name;
            
        }
        return $doc_title;
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
                'is_active' => $student->is_active,
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
            'is_active'         => 'is_active',
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