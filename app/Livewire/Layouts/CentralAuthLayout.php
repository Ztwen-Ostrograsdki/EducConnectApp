<?php

namespace App\Livewire\Layouts;

use Livewire\Attributes\On;
use Livewire\Component;

class CentralAuthLayout extends Component
{
    public $selectedYear;

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->selectedYear = $schoolYear;
    }
    
    public function render()
    {
        return view('livewire.layouts.central-auth-layout');
    }
}
