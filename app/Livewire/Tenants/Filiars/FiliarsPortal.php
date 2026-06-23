<?php

namespace App\Livewire\Tenants\Filiars;

use App\Models\Filiar;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Portail des filières")]
class FiliarsPortal extends Component
{
    use WireUiActions;


    public $counter = 0;




    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }



    public function render()
    {
        $filiars = Filiar::orderByDesc('name')->get();

        return view('livewire.tenants.filiars.filiars-portal', compact('filiars'));
    }
}
