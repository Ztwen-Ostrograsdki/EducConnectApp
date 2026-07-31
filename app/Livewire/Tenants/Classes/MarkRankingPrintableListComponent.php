<?php

namespace App\Livewire\Tenants\Classes;

use App\Models\SchoolYear;
use App\Services\MarksServices\MarkRankingPrintColumns;
use App\Services\MarksServices\MarkRankingPrintQuery;
use App\Services\MarksServices\MarkRankingPrintSessionConfig;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.print-layout')]
#[Title("Aperçu — Classement des apprenants")]
class MarkRankingPrintableListComponent extends Component
{
    public array $tableColumns = [];

    public function mount(): void
    {
        $subjectId = MarkRankingPrintSessionConfig::subjectId();

        $this->tableColumns = MarkRankingPrintColumns::resolve(null, (bool) $subjectId);
    }

    public function render()
    {
        $schoolYear = SchoolYear::current()->first();
        $period = MarkRankingPrintSessionConfig::period();
        $subjectId = MarkRankingPrintSessionConfig::subjectId();
        $config = MarkRankingPrintSessionConfig::filterConfig();
        $targeted = MarkRankingPrintSessionConfig::targeted();
        $limit = MarkRankingPrintSessionConfig::limit();
        $groupedBy = MarkRankingPrintSessionConfig::groupedBy();

        $devoirsType = tenant()->devoirs_type ?? 'devoir1-devoir2';

        $rows = ($schoolYear && $period)
            ? MarkRankingPrintQuery::getFormattedRows(
                $config, $schoolYear->id, $period, $this->tableColumns,
                $devoirsType, $subjectId, $targeted, $limit, $groupedBy
            )
            : [];

        $pdf_title = MarkRankingPrintQuery::resolveDocTitle($config, $subjectId, $targeted, $limit, $groupedBy);

        return view('livewire.tenants.classes.mark-ranking-printable-list-component', [
            'rows'         => $rows,
            'printed_at'   => now()->isoFormat('dddd D MMMM YYYY [à] HH:mm'),
            'allStudents'  => count($rows),
            'pdf_title'    => $pdf_title,
            'tableColumns' => $this->tableColumns,
            'period'       => $period,
            'schoolYear'   => $schoolYear,
        ]);
    }
}