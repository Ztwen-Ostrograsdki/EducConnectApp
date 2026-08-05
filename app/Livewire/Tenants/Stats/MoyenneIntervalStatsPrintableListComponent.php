<?php

namespace App\Livewire\Tenants\Stats;

use App\Models\SchoolYear;
use App\Services\ClassesServices\MoyenneIntervalStatsQuery;
use App\Services\ClassesServices\MoyenneIntervalStatsSessionConfig;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.print-layout')]
#[Title("Aperçu — Statistiques des moyennes")]
class MoyenneIntervalStatsPrintableListComponent extends Component
{
    public function render()
    {
        $schoolYear = SchoolYear::current()->first();
        $period = MoyenneIntervalStatsSessionConfig::period();
        $config = MoyenneIntervalStatsSessionConfig::filterConfig();
        $groupedBy = MoyenneIntervalStatsSessionConfig::groupedBy();
        $breakpoints = MoyenneIntervalStatsSessionConfig::breakpoints();

        $result = ($schoolYear && $period)
            ? MoyenneIntervalStatsQuery::computeRows($config, $schoolYear->id, $period, $breakpoints, $groupedBy)
            : ['rows' => [], 'intervalLabels' => []];

        $pdf_title = $schoolYear
            ? MoyenneIntervalStatsQuery::resolveDocTitle($config, $groupedBy, (int) $period, $schoolYear)
            : 'Statistiques des moyennes';

        return view('livewire.tenants.stats.moyenne-interval-stats-printable-list-component', [
            'rows'           => $result['rows'],
            'intervalLabels' => $result['intervalLabels'],
            'printed_at'     => now()->isoFormat('dddd D MMMM YYYY [à] HH:mm'),
            'pdf_title'      => $pdf_title,
            'period'         => $period,
            'schoolYear'     => $schoolYear,
        ]);
    }
}