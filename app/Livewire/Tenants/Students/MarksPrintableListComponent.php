<?php

namespace App\Livewire\Tenants\Students;


use App\Models\SchoolYear;
use App\Services\MarksServices\MarkPrintColumns;
use App\Services\MarksServices\MarkPrintQuery;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.print-layout')]
#[Title("Aperçu — Liste des notes")]
class MarksPrintableListComponent extends Component
{
    public array $tableColumns = [];

    protected function filterConfigFromSession(): array
    {
        return [
            "classe_id"         => session('print_marks_classe_selected'),
            "filiar_id"         => session('print_marks_filiar_selected'),
            "serial_id"         => session('print_marks_serial_selected'),
            "promotion_id"      => session('print_marks_promotion_selected'),
            "promotionInGroups" => session('print_marks_promotions_grouped_selected'),
            "level"             => session('print_marks_level_selected'),
            "leavesConfig"      => session('print_marks_leaves_status', 'onlyActives'),
        ];
    }

    protected function subjectIdFromSession(): ?int
    {
        $value = session('print_marks_subject_selected');

        return $value ? (int) $value : null;
    }

    protected function periodFromSession(): ?int
    {
        $value = session('print_marks_period_selected');

        return $value ? (int) $value : null;
    }

    public function mount(): void
    {
        $devoirsType = tenant()->devoirs_type ?? 'devoir1-devoir2';

        $this->tableColumns = MarkPrintColumns::resolve(null, $devoirsType);
    }

    public function render()
    {
        $schoolYear = SchoolYear::current()->first();
        $period = $this->periodFromSession();
        $subjectId = $this->subjectIdFromSession();
        $config = $this->filterConfigFromSession();

        $rows = ($schoolYear && $period)
            ? MarkPrintQuery::getFormattedRows(
                $config,
                $schoolYear->id,
                $period,
                $this->tableColumns,
                tenant()->devoirs_type ?? 'devoir1-devoir2',
                $subjectId
            )
            : [];

        $pdf_title = ($schoolYear && $period)
            ? MarkPrintQuery::resolveDocTitle($config, $subjectId, $period, $schoolYear->id)
            : 'Liste des notes';

        return view('livewire.tenants.students.marks-printable-list-component', [
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