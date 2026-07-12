<?php

namespace App\Livewire\Tenants\Students;

use App\Models\SchoolYear;
use App\Services\StudentsServices\StudentPrintColumns;
use App\Services\StudentsServices\StudentPrintQuery;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.print-layout')]
#[Title("Aperçu — Liste des apprenants")]
class StudentsPrintableListComponent extends Component
{
    public ?string $pdf_title = "Liste des apprenants";

    public array $tableColumns = [];

    /**
     * Colonnes de secours si aucune config n'a été transmise
     * (doit rester alignée avec StudentPrintColumns::$defaultOrder)
     */
    public array $defaultColumns = [
        ['key' => 'educMaster',        'label' => 'EducMaster',       'position' => 1, 'type' => 'text'],
        ['key' => 'full_name',         'label' => 'Nom & Prénom',     'position' => 2, 'type' => 'text'],
        ['key' => 'gender',            'label' => 'Sexe',             'position' => 3, 'type' => 'gender'],
        ['key' => 'father_full_name',  'label' => 'Père',             'position' => 4, 'type' => 'text'],
        ['key' => 'mother_full_name',  'label' => 'Mère',             'position' => 5, 'type' => 'text'],
        ['key' => 'classe.name',       'label' => 'Classe',           'position' => 6, 'type' => 'text'],
        ['key' => 'contacts',          'label' => 'Contact',          'position' => 7, 'type' => 'phone'],
        ['key' => 'birth_date',        'label' => 'Naissance / Âge',  'position' => 8, 'type' => 'age'],
        ['key' => 'status',            'label' => 'Statut',           'position' => 9, 'type' => 'badge'],
        ['key' => 'observations',      'label' => 'Obs.',             'position' => 10, 'type' => 'text'],
    ];

    /**
     * Largeurs relatives par colonne — normalisées à l'exécution pour
     * garantir un total de 100% peu importe le nombre de colonnes cochées.
     */
    public static array $columnWidths = [
        'educMaster'       => 9,
        'full_name'        => 17,
        'gender'           => 4,
        'father_full_name' => 11,
        'mother_full_name' => 11,
        'classe.name'      => 9,
        'contacts'         => 15,
        'birth_date'       => 11,
        'status'           => 8,
        'observations'     => 9,
    ];

    /**
     * Résout et formate la valeur d'une colonne pour un apprenant donné.
     * Appelée statiquement depuis le Blade, car ce composant n'est jamais
     * instancié dans le pipeline PDF (rendu via view() brut par Browsershot).
     */
    public static function getData($student, array $column): string
    {
        $value = static::resolveValue($student, $column['key']);

        return static::formatValue($value, $column['type'] ?? 'text');
    }

    protected static function resolveValue($student, string $key): mixed
    {
        return match ($key) {
            'full_name'   => $student->getFullName(),
            'classe.name' => static::resolveClasseLabel($student),
            'birth_date'  => $student->birth_date,
            default       => data_get($student, $key),
        };
    }

    protected static function resolveClasseLabel($student): ?string
    {
        $classe = $student->currentClasse()?->classe;

        return $classe ? ($classe->code ?: $classe->name) : null;
    }

    protected static function formatValue(mixed $value, string $type): string
    {
        return match ($type) {
            'gender' => $value ? e(strtoupper(substr($value, 0, 1))) : '—',

            'age' => $value
                ? '<div class="flex flex-col items-center">'
                    . '<span class="text-[0.76rem] text-black">' . __formatDate($value) . '</span>'
                    . '<span class="text-[0.66rem] font-normal text-gray-600 mt-0.5">' . __getAge($value) . ' ans</span>'
                  . '</div>'
                : '—',

            'badge' => static::badgeMarkup($value),

            'phone' => $value ? e($value) : '—',

            default => ($value !== null && $value !== '') ? e((string) $value) : '—',
        };
    }

    /**
     * Classes Tailwind littérales par statut — nécessaire pour le scan JIT
     * (une concaténation type "bg-{$status}-100" ne serait jamais détectée).
     */
    protected static function badgeMarkup(mixed $status): string
    {
        [$classes, $label] = match ($status) {
            'active', true, 1 => ['bg-green-100 text-green-800 border border-green-300', 'Actif'],
            'conge'            => ['bg-yellow-100 text-yellow-800 border border-yellow-300', 'Congé'],
            'suspend'          => ['bg-purple-100 text-purple-800 border border-purple-300', 'Suspendu'],
            default            => ['bg-red-100 text-red-800 border border-red-300', 'Inactif'],
        };

        return '<span class="inline-block px-2.5 py-0.5 rounded-full text-[0.66rem] font-bold uppercase tracking-wide whitespace-nowrap ' . $classes . '">'
            . $label . '</span>';
    }

    /**
     * Calcule la largeur en % de chaque colonne active, normalisée sur 96%
     * (les 4% restants sont réservés à la colonne "#").
     */
    public static function normalizedWidths(array $tableColumns): array
    {
        $raw = collect($tableColumns)
            ->mapWithKeys(fn (array $col) => [
                $col['key'] => static::$columnWidths[$col['key']] ?? 10,
            ]);

        $sum = $raw->sum();

        if ($sum <= 0) {
            return [];
        }

        return $raw->map(fn ($w) => round(($w / $sum) * 96, 2) . '%')->toArray();
    }

    protected function filterConfigFromSession(): array
    {
        return [
            "trashedConfig"     => session('print_students_trashed_status', 'withoutTrashed'),
            "leavesConfig"      => session('print_students_leaves_status', 'onlyActives'),
            "hasClasseConfig"   => session('print_students_has_classe_status', 'onlyHasClasse'),
            "classe_id"         => session('print_students_classe_selected'),
            "filiar_id"         => session('print_students_filiar_selected'),
            "serial_id"         => session('print_students_serial_selected'),
            "promotion_id"      => session('print_students_promotion_selected'),
            "promotionInGroups" => session('print_students_promotions_grouped_selected'),
            "gender"            => session('print_students_gender_selected'),
            "city"              => session('print_students_city_selected'),
            "department"        => session('print_students_department_selected'),
        ];
    }

    public function mount(): void
    {
        $this->tableColumns = StudentPrintColumns::build(
            session()->get('student-list-selected-columns', [])
        );
    }

    public function render()
    {
        $schoolYearId = SchoolYear::current()->first()?->id;

        $students = $schoolYearId
            ? StudentPrintQuery::get($this->filterConfigFromSession(), $schoolYearId)
            : collect();

        return view('livewire.tenants.students.students-printable-list-component', [
            'students'     => $students,
            'printed_at'   => now()->isoFormat('dddd D MMMM YYYY [à] HH:mm'),
            'allStudents'  => $students->count(),
            'pdf_title'    => $this->pdf_title,
            'tableColumns' => $this->tableColumns,
        ]);
    }
}