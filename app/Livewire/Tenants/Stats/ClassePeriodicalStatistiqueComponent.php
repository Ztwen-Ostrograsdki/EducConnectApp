<?php

namespace App\Livewire\Tenants\Stats;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
class ClassePeriodicalStatistiqueComponent extends Component
{
    public function render()
    {
        return view('livewire.tenants.stats.classe-periodical-statistique-component');
    }
}
