<?php

namespace App\Livewire\Tenants\Reports;

use App\Models\SchoolYear;
use App\Services\MarksServices\MarkDiagnosticColumns;
use App\Services\MarksServices\MarkDiagnosticQuery;
use App\Services\MarksServices\MarkDiagnosticSessionConfig;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.print-layout')]
#[Title("Aperçu — Diagnostic des notes")]
class MarksDiagnosticPrintableListComponent extends Component
{
    public array $tableColumns = [];

    public function mount(): void
    {
        $this->tableColumns = MarkDiagnosticColumns::resolve(null);
    }

    public function render()
    {
        $schoolYear = SchoolYear::current()->first();
        $period = MarkDiagnosticSessionConfig::period();
        $config = MarkDiagnosticSessionConfig::filterConfig();
        $status = MarkDiagnosticSessionConfig::status();
        $markTypes = MarkDiagnosticSessionConfig::markTypes();

        $rows = ($schoolYear && $period && ! empty($markTypes))
            ? MarkDiagnosticQuery::getFormattedRows(
                $config, $schoolYear->id, $period, $this->tableColumns, $markTypes, $status
            )
            : [];

        $pdf_title = $schoolYear
            ? MarkDiagnosticQuery::resolveDocTitle($config, (int) $period, $schoolYear, $status)
            : 'Diagnostic des notes';

        return view('livewire.tenants.reports.marks-diagnostic-printable-list-component', [
            'rows'         => $rows,
            'printed_at'   => now()->isoFormat('dddd D MMMM YYYY [à] HH:mm'),
            'allRows'      => count($rows),
            'pdf_title'    => $pdf_title,
            'tableColumns' => $this->tableColumns,
            'period'       => $period,
            'schoolYear'   => $schoolYear,
        ]);
    }
}