<?php

namespace App\Livewire\Tenants\Students;

use App\Models\SchoolYear;
use App\Services\StudentsServices\StudentPrintColumns;
use App\Services\StudentsServices\StudentPrintQuery;
use App\Services\StudentsServices\StudentPrintSessionConfig;
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
     * Appelée statiquement — utilisée aussi bien par le pipeline PDF batché
     * (via StudentPrintQuery::getFormattedRows) que par tout appel isolé.
     *
     * @param array $context Valeurs pré-résolues pour éviter les requêtes N+1
     *                        (ex: 'classeLabel' déjà calculé en amont via une map).
     */
    public static function getData($student, array $column, array $context = []): string
    {
        $value = static::resolveValue($student, $column['key'], $context);

        return static::formatValue($value, $column['type'] ?? 'text');
    }

    protected static function resolveValue($student, string $key, array $context = []): mixed
    {
        return match ($key) {
            'full_name'   => $student->getFullName(),
            'classe.name' => $context['classeLabel'] ?? static::resolveClasseLabel($student),
            'birth_date'  => $student->birth_date,
            default       => data_get($student, $key),
        };
    }

    /**
     * Fallback si aucun contexte n'est fourni. Reste une requête à la volée
     * (currentClasse() n'est pas une relation eager-loadable) — acceptable
     * en usage isolé, mais jamais à appeler en boucle sans contexte pré-résolu.
     */
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
                ? '<div class="cell-flex-col">'
                    . '<span class="age-date">' . __formatDate($value) . '</span>'
                    . '<span class="age-years">' . __getAge($value) . ' ans</span>'
                  . '</div>'
                : '—',

            'badge' => static::badgeMarkup($value),

            'phone' => $value ? e($value) : '—',

            default => ($value !== null && $value !== '') ? e((string) $value) : '—',
        };
    }

    protected static function badgeMarkup(mixed $status): string
    {
        $modifier = match ($status) {
            'active', true, 1 => 'actif',
            'conge'            => 'conge',
            'suspend'          => 'suspend',
            default            => 'inactif',
        };

        $label = match ($modifier) {
            'actif'   => 'Actif',
            'conge'   => 'Congé',
            'suspend' => 'Suspendu',
            default   => 'Inactif',
        };

        return '<span class="statut-badge statut-badge--' . $modifier . '">' . $label . '</span>';
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

    public function mount(): void
    {
        $this->tableColumns = StudentPrintColumns::resolve();
    }

    public function render()
    {
        $schoolYearId = SchoolYear::current()->first()?->id;

        $columns = $this->tableColumns ?: $this->defaultColumns;

        $rows = $schoolYearId
            ? StudentPrintQuery::getFormattedRows(StudentPrintSessionConfig::filterConfig(), $schoolYearId, $columns)
            : [];

        return view('livewire.tenants.students.students-printable-list-component', [
            'rows'         => $rows,
            'printed_at'   => now()->isoFormat('dddd D MMMM YYYY [à] HH:mm'),
            'allStudents'  => count($rows),
            'pdf_title'    => $this->pdf_title,
            'tableColumns' => $columns,
        ]);
    }
}