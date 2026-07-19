<?php

namespace App\Livewire\Tenants\Classes;

use App\Models\SchoolYear;
use App\Services\ClassesServices\ClassePrintColumns;
use App\Services\ClassesServices\ClassePrintQuery;
use App\Services\ClassesServices\ClassePrintSessionConfig;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.print-layout')]
#[Title("Aperçu — Liste des classes")]
class ClassesPrintableListComponent extends Component
{
    public array $tableColumns = [];

    public static array $columnWidths = [
        'name'             => 10,
        'filiar'           => 9,
        'serial'           => 9,
        'promotion'        => 9,
        'pp'               => 12,
        'respo1'           => 11,
        'respo2'           => 11,
        'effectif_total'   => 6,
        'effectif_garcons' => 6,
        'effectif_filles'  => 6,
        'teachers_count'   => 6,
        'leaves_count'     => 7,
        'best_student'     => 11,
        'worst_student'    => 11,
        'best_boy'         => 9,
        'best_girl'        => 9,
        'observations'     => 9,
    ];

    public static function getData($classe, array $column, array $context = []): string
    {
        $value = static::resolveValue($classe, $column['key'], $context);

        return static::formatValue($value, $column['type'] ?? 'text');
    }

    protected static function resolveValue($classe, string $key, array $context = []): mixed
    {
        return match ($key) {
            'filiar'           => $classe->filiar?->code ?: $classe->filiar?->name,
            'serial'           => $classe->serial?->code ?: $classe->serial?->name,
            'promotion'        => $classe->promotion?->name,
            'pp'               => $classe->principal?->getFullName(),
            'respo1'           => $classe->respo1?->getFullName(),
            'respo2'           => $classe->respo2?->getFullName(),
            'effectif_total'   => $context['studentsTotal'] ?? 0,
            'effectif_garcons' => $context['studentsGarcons'] ?? 0,
            'effectif_filles'  => $context['studentsFilles'] ?? 0,
            'teachers_count'   => $context['teachersCount'] ?? 0,
            'leaves_count'     => $context['leavesCount'] ?? 0,
            'best_student'     => $context['bestStudent'] ?? null,   // en attente
            'worst_student'    => $context['worstStudent'] ?? null,  // en attente
            'best_boy'         => $context['bestBoy'] ?? null,       // en attente
            'best_girl'        => $context['bestGirl'] ?? null,      // en attente
            'observations'     => null,
            default            => data_get($classe, $key),
        };
    }

    protected static function formatValue(mixed $value, string $type): string
    {
        return match ($type) {
            'blank' => '&nbsp;',
            default => ($value !== null && $value !== '') ? e((string) $value) : '—',
        };
    }

    public static function normalizedWidths(array $tableColumns): array
    {
        $raw = collect($tableColumns)
            ->mapWithKeys(fn (array $col) => [$col['key'] => static::$columnWidths[$col['key']] ?? 10]);

        $sum = $raw->sum();

        if ($sum <= 0) return [];

        return $raw->map(fn ($w) => round(($w / $sum) * 96, 2) . '%')->toArray();
    }

    public function mount(): void
    {
        $this->tableColumns = ClassePrintColumns::resolve();
    }

    public function render()
    {
        $schoolYearId = SchoolYear::current()->first()?->id;
        $config = ClassePrintSessionConfig::filterConfig();

        $rows = $schoolYearId
            ? ClassePrintQuery::getFormattedRows($config, $schoolYearId, $this->tableColumns)
            : [];

        return view('livewire.tenants.classes.classes-printable-list-component', [
            'rows'         => $rows,
            'printed_at'   => now()->isoFormat('dddd D MMMM YYYY [à] HH:mm'),
            'allClasses'   => count($rows),
            'pdf_title'    => $schoolYearId ? ClassePrintQuery::resolveDocTitle($config, $schoolYearId) : 'Liste des classes',
            'tableColumns' => $this->tableColumns,
        ]);
    }
}