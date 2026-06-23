<?php

namespace App\Livewire\Layouts;

use Livewire\Attributes\On;
use Livewire\Component;

class TenantAuthLayout extends Component
{

    public $counter = 0;
    
    #[On("NewSchoolYearCreatedLiveEvent")]
    public function newSchoolYearCreated()
    {
        $this->counter++;
    }

    #[On("SchoolYearUpdatedLiveEvent")]
    public function schoolYearUpdated()
    {
        $this->counter++;
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    public function render()
    {

        return view('livewire.layouts.tenant-auth-layout');
    }
}
