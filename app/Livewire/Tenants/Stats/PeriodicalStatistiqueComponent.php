<?php

namespace App\Livewire\Tenants\Stats;

use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class PeriodicalStatistiqueComponent extends Component
{

    public ?string $school_year_selected; 

    public ?string $period_type_selected; 

    public ?string $promotion_selected; 

    public ?string $filiar_selected; 

    public ?string $serial_selected; 

    public ?string $classe_selected; 

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->school_year_selected = $schoolYear;
    }

    public function updatedPeriodTypeSelected(?string $period_type_selected)
    {
        session()->put('tenant_periodic_stats_period_type_selected', $period_type_selected);
    }

    public function updatedPromotionSelected(?string $promotion_selected)
    {
        session()->put('tenant_periodic_stats_promotion_selected', $promotion_selected);
    }

    public function updatedFiliarSelected(?string $filiar_selected)
    {
        session()->put('tenant_periodic_stats_filiar_selected', $filiar_selected);
    }

    public function updatedSerialSelected(?string $serial_selected)
    {
        session()->put('tenant_periodic_stats_serial_selected', $serial_selected);
    }
    
    public function updatedClasseSelected(?string $classe_selected)
    {
        session()->put('tenant_periodic_stats_classe_selected', $classe_selected);
    }

    public function render()
    {
        return view('livewire.tenants.stats.periodical-statistique-component');
    }
}
