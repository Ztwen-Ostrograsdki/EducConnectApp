<?php

namespace App\Livewire\Tenants\Reports;


use App\Models\SchoolYear;
use App\Services\BulletinsServices\BulletinPrintQuery;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.print-layout')]
#[Title("Aperçu — Bulletins")]
class BulletinsPrintableListComponent extends Component
{
    protected function filterConfigFromSession(): array
    {
        return [
            "classe_id"         => session('print_bulletins_classe_selected'),
            "filiar_id"         => session('print_bulletins_filiar_selected'),
            "serial_id"         => session('print_bulletins_serial_selected'),
            "promotion_id"      => session('print_bulletins_promotion_selected'),
            "promotionInGroups" => session('print_bulletins_promotions_grouped_selected'),
            "level"             => session('print_bulletins_level_selected'),
            "leavesConfig"      => session('print_bulletins_leaves_status', 'onlyActives'),
        ];
    }

    public function render()
    {
        $schoolYear = SchoolYear::current()->first();
        $period = session('print_bulletins_period_selected') ?: $schoolYear?->active_period;
        $config = $this->filterConfigFromSession();

        $bulletins = ($schoolYear && $period)
            ? BulletinPrintQuery::getBulletinsData($config, $schoolYear->id, (int) $period, $schoolYear)
            : [];

        $pdf_title = $schoolYear
            ? BulletinPrintQuery::resolveDocTitle($config, (int) $period, $schoolYear)
            : 'Bulletins';

        return view('livewire.tenants.reports.bulletins-printable-list-component', [
            'bulletins'  => $bulletins,
            'printed_at' => now()->isoFormat('dddd D MMMM YYYY [à] HH:mm'),
            'pdf_title'  => $pdf_title,
            'period'     => $period,
            'schoolYear' => $schoolYear,
        ]);
    }
}