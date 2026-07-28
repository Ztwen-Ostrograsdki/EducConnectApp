<?php

namespace App\Livewire\Tenants\Classes\Sections;

use App\Livewire\Tenants\ActionsTraits\TutorsActions;
use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Tutor;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class ClasseParentsPage extends Component
{
    use WireUiActions, WithPagination, TutorsActions;
    
    public string $classroom;

    public ?Classe $classe = null;

    public ?string $classe_slug;

    public ?string $search = '';

    public ?string $status = null;

    public $counter = 0;

    public $perPage = 20;

    public function clearFilters()
    {
        $this->reset('search', 'status');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    #[Computed]
    public function tutors()
    {
        return Tutor::query()
        ->select('tutors.*')
        ->join('users', 'users.id', '=', 'tutors.user_id')
        ->with(['user'])
        ->withTrashed()
        ->whereHas('myChildren', fn($qcl2) => 
                $qcl2->whereHas('student', fn($qcl3) => 
                    $qcl3->whereHas('classes', fn($qcl4) => 
                        $qcl4->where('classe_id', $this->classe->id)->where('is_active', true)->where('school_year_id', $this->activeYear->id)->whereNull('ended_at')
                    )
                )
            )
        ->when($this->search, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('email', 'like', "%{$this->search}%");
                $query->orwhere('name', 'like', "%{$this->search}%");
                $query->orwhere('prenames', 'like', "%{$this->search}%");
                $query->orwhere('contacts', 'like', "%{$this->search}%");
                $query->orwhere('adresse', 'like', "%{$this->search}%");
                $query->orwhere('city', 'like', "%{$this->search}%");
                $query->orwhere('department', 'like', "%{$this->search}%");
                $query->orwhere('country', 'like', "%{$this->search}%");
                $query->orwhere('gender', 'like', "%{$this->search}%");
                $query->orwhere('birth_date', 'like', "%{$this->search}%");
                $query->orwhere('birth_place', 'like', "%{$this->search}%");
                $query->orwhere('job_name', 'like', "%{$this->search}%");
            });
        })
        ->when($this->status, function (Builder $query) {
            match($this->status){
                'actives' => $query->where('is_active', true)->whereNull('tutors.deleted_at'),
                'desactives' => $query->where('is_active', false)->whereNull('tutors.deleted_at'),
                'corbeille' => $query->whereNotNull('tutors.deleted_at'),
            };
        })
        ->orderBy('users.name')
        ->orderBy('users.prenames')
        ->paginate($this->perPage);
    }
    
    public function render()
    {
        return view('livewire.tenants.classes.sections.classe-parents-page');
    }
}
