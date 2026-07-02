<?php

namespace App\Livewire\Tenants\Subjects;

use App\Events\DataUpdatedEvent;
use App\Livewire\Tenants\ActionsTraits\SubjectsActions;
use App\Models\SchoolYear;
use App\Models\Subject;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Portail des matières")]
class SubjectsPortal extends Component
{

    use WireUiActions, WithPagination, SubjectsActions;

    public $perPage = 5;

    public $counter = 0;

    public ?string $search = null;

    public ?string $is_active = 'actives';

    public ?string $type = null;

    public ?string $school_year_selected;

    public function mount()
    {
        if(session()->has('subjects_type_seleteted')){

            $this->type = session('subjects_type_seleteted');
        }

        if(session()->has('subjects_is_active_selected')){

            $this->is_active = session('subjects_is_active_selected');
        }
    }

    public function clearFilters()
    {
        $this->reset('search', 'type', 'is_active');

        session()->forget('subjects_type_seleteted');

    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }
    
    #[On('yearChanged')]
    public function onYearChanged(?string $schoolYear)
    {
        $this->school_year_selected = $schoolYear;
    }


    public function updatingType()
    {
        $this->resetPage();
    }


    public function updatedType(?string $value)
    {
        session()->put('subjects_type_seleteted', $this->type);
    }

    public function updatedIsActive(?string $value)
    {
        session()->put('subjects_is_active_selected', $this->is_active);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    } 
    
    public function updatingIsActive()
    {
        $this->resetPage();
    }

    #[Computed]
    public function subjects()
    {
        return Subject::withTrashed($this->is_active && $this->is_active === 'corbeille')
                        ->when($this->search, fn($qs) =>
                            $qs->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('code', 'like', '%' . $this->search . '%')
                        )
                        ->when($this->is_active && $this->is_active === 'actives', fn($qa) =>
                            $qa->where('is_active', true)
                        )
                        ->when($this->is_active && $this->is_active === 'desactives', fn($qa) =>
                            $qa->where('is_active', false)
                        )
                        ->when($this->is_active && $this->is_active === 'corbeille', fn($qa) =>
                            $qa->whereNotNull('deleted_at')
                        )
                        ->when($this->type, fn($qt) =>
                            $qt->where('type', $this->type)
                        )
                        ->orderBy('name')
                        ->paginate($this->perPage);
    }


    


    public function render()
    {
        return view('livewire.tenants.subjects.subjects-portal');
    }
}
