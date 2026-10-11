<?php

namespace App\Livewire\Tenants\TimePlans;

use App\Models\SchoolYear;
use App\Models\Subject;
use App\Services\TimePlanServices\TimePlanPrintBuilder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.print-layout')]
#[Title('Aperçu — Emplois du temps')]
class TimePlanPrintableListComponent extends Component
{
    public function render()
    {
        $schoolYearId = session('print_timeplan_school_year_id')
            ?? SchoolYear::current()->first()?->id;

        $mode = session('print_timeplan_mode', 'classes');
        $config = session('print_timeplan_config', []);

        $plans = [];
        $pdf_title = 'Emplois du temps';
        $context_label = null;
        $schoolYear = $schoolYearId ? SchoolYear::query()->find($schoolYearId) : null;

        if ($schoolYearId) {
            $builder = TimePlanPrintBuilder::forYear((int) $schoolYearId);

            $plans = match ($mode) {
                'teachers' => $builder->buildForTeachers($config['teacher_ids'] ?? []),
                'subject' => $builder->buildForSubjectTeachers((int) ($config['subject_id'] ?? 0)),
                default => $builder->buildForClasses(
                    $config['classe_ids'] ?? [],
                    $config['plan_status'] ?? 'published'
                ),
            };

            $subjectName = null;
            if ($mode === 'subject' && !empty($config['subject_id'])) {
                $subjectName = Subject::query()->find($config['subject_id'])?->name;
            }

            $pdf_title = $builder->resolveDocTitle($mode, ['subject_name' => $subjectName]);

            $context_label = match ($mode) {
                'subject' => $subjectName ? "Matière : {$subjectName}" : null,
                'teachers' => 'Enseignants sélectionnés',
                default => 'Classes sélectionnées',
            };
        }

        return view('livewire.tenants.timeplans.time-plan-printable-list-component', [
            'plans'         => $plans,
            'printed_at'    => now()->isoFormat('dddd D MMMM YYYY [à] HH:mm'),
            'pdf_title'     => $pdf_title,
            'context_label' => $context_label,
            'schoolYear'    => $schoolYear,
        ]);
    }
}
