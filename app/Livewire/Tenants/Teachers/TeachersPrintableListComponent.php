<?php

namespace App\Livewire\Tenants\Teachers;

use App\Services\TeachersServices\TeacherPrintColumns;
use App\Services\TeachersServices\TeacherPrintQuery;
use App\Services\TeachersServices\TeacherPrintSessionConfig;
use App\Models\SchoolYear;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.print-layout')]
#[Title("Aperçu — Liste des enseignants")]
class TeachersPrintableListComponent extends Component
{
    public array $tableColumns = [];

    public static array $columnWidths = [
        'identifiant'   => 8,
        'full_name'     => 16,
        'gender'        => 4,
        'email'         => 14,
        'contacts'      => 12,
        'classes'       => 12,
        'subjects'      => 12,
        'pp'            => 10,
        'ae'            => 10,
        'access_status' => 8,
        'specialties'   => 10,
        'birth_date'    => 11,
        'status'        => 8,
        'observations'  => 9,
        'emargement'  => 9,
    ];

    public static function getData($teacher, array $column, array $context = []): string
    {
        $value = static::resolveValue($teacher, $column['key'], $context);

        return static::formatValue($value, $column['type'] ?? 'text');
    }

    protected static function resolveValue($teacher, string $key, array $context = []): mixed
    {
        return match ($key) {
            'full_name'      => $teacher->getFullName(),
            'gender'         => $teacher->user?->gender,
            'identifiant'    => $teacher->identifiant,
            'contacts'       => $teacher->user?->contacts,
            'birth_date'     => $teacher->user?->birth_date,
            'classes'        => $context['classesLabel'] ?? null,
            'subjects'       => $context['subjectsLabel'] ?? null,
            'pp'             => $context['ppLabel'] ?? null,
            'ae'             => $context['aeLabel'] ?? null,
            'access_status'  => $context['accessStatus'] ?? null,
            'specialties'    => is_array($teacher->specialties) ? implode(', ', $teacher->specialties) : $teacher->specialties,
            'observations'   => null, 
            'emargement'   => null, 
            default          => data_get($teacher, $key),
        };
}

    protected static function formatValue(mixed $value, string $type): string
    {
        return match ($type) {
            'gender' => $value ? e(strtoupper(substr($value, 0, 1))) : '',

            'blank' => '&nbsp;',

            'age' => $value
                ? '<div class="cell-flex-col">'
                    . '<span class="age-date">' . __formatDate($value) . '</span>'
                    . '<span class="age-years">' . __getAge($value) . ' ans</span>'
                  . '</div>'
                : '',

            'badge' => static::statusBadgeMarkup($value),

            'access_badge' => static::accessBadgeMarkup($value),

            'phone', 'email' => $value ? e($value) : '',

            'list' => $value ? e($value) : '',

            default => ($value !== null && $value !== '') ? e((string) $value) : '',
        };
    }

    protected static function statusBadgeMarkup(mixed $status): string
    {
        $modifier = match ($status) {
            'active', true, 1 => 'actif',
            default            => 'inactif',
        };

        $label = $modifier === 'actif' ? 'Actif' : 'Inactif';

        return '<span class="statut-badge statut-badge--' . $modifier . '">' . $label . '</span>';
    }

    protected static function accessBadgeMarkup(mixed $status): string
    {
        [$modifier, $label] = match ($status) {
            'active'    => ['actif', 'Actif'],
            'pending'   => ['conge', 'En attente'],
            'suspended' => ['suspend', 'Suspendu'],
            default     => ['inactif', 'Aucun accès'],
        };

        return '<span class="statut-badge statut-badge--' . $modifier . '">' . $label . '</span>';
    }

    public static function normalizedWidths(array $tableColumns): array
    {
        $raw = collect($tableColumns)
            ->mapWithKeys(fn (array $col) => [$col['key'] => static::$columnWidths[$col['key']] ?? 10]);

        $sum = $raw->sum();

        if ($sum <= 0) {
            return [];
        }

        return $raw->map(fn ($w) => round(($w / $sum) * 96, 2) . '%')->toArray();
    }

    public function mount(): void
    {
        $this->tableColumns = TeacherPrintColumns::resolve();

    }

    public function render()
    {
        $schoolYearId = SchoolYear::current()->first()?->id;

        $rows = $schoolYearId
            ? TeacherPrintQuery::getFormattedRows(TeacherPrintSessionConfig::filterConfig(), $schoolYearId, $this->tableColumns)
            : [];

        $pdf_title = TeacherPrintQuery::resolveDocTitle(TeacherPrintSessionConfig::filterConfig());

        return view('livewire.tenants.teachers.teachers-printable-list-component', [
            'rows'         => $rows,
            'printed_at'   => now()->isoFormat('dddd D MMMM YYYY [à] HH:mm'),
            'allTeachers'  => count($rows),
            'pdf_title'    => $pdf_title,
            'tableColumns' => $this->tableColumns,
        ]);
    }
}