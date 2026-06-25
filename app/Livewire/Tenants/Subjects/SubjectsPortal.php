<?php

namespace App\Livewire\Tenants\Subjects;

use App\Models\Subject;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Portail des matières")]
class SubjectsPortal extends Component
{

    use WireUiActions;
    

    public $counter = 0;

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    
    public ?string $school_year_selected;

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->school_year_selected = $schoolYear;
    }

    public function render()
    {
        $subjects = Subject::orderByDesc('name')->get();

        return view('livewire.tenants.subjects.subjects-portal', compact('subjects'));
    }
}
