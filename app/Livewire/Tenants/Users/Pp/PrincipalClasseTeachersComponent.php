<?php

namespace App\Livewire\Tenants\Users\Pp;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Teacher;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Title("ESPACE PP - LISTE DES PROFS")]
class PrincipalClasseTeachersComponent extends Component
{
    use WireUiActions, WithPagination;

    public ?int $period = null;

    public ?string $classe_slug = null;

    public $counter = 0;

    public function mount(string $classe_slug)
    {
        if (!$this->classe_slug) {
            return abort(404);
        }

        $this->classe_slug = $classe_slug;

        $this->loadActivePeriod();
    }

    public function loadActivePeriod()
    {
        if($this->activeYear && $this->activeYear->is_active && $this->activeYear->active_period){

            $this->period = $this->activeYear->active_period;
        }

    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }


    #[Computed]
    public function classe()
    {
        if (!$this->classe_slug && !$this->activeYear) {
            return null;
        }

        $classe = Classe::firstWhere('slug', $this->classe_slug);

        if(!$classe) return abort(404);

        return $classe;
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }

    #[Computed]
    public function teachers()
    {
        
        return Teacher::query()
        ->select('teachers.*')
        ->join('users', 'users.id', '=', 'teachers.user_id')
        ->whereHas('classeSubjects', fn ($q) =>
            $q->where('school_year_id', $this->activeYear->id)
              ->where('classe_id', $this->classe->id)
              ->where('is_active', true)
              ->whereNull('ended_at')
        )
        ->orderBy('users.name')
        ->orderBy('users.prenames')
        ->paginate(20);


        
    }


    public function render()
    {
        return view('livewire.tenants.users.pp.principal-classe-teachers-component');
    }
}
