<?php

namespace App\Livewire\Tenants\Users\Pp;

use App\Models\Classe;
use App\Models\SchoolYear;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;


#[Title("ESPACE PP - NOTES DE CLASSE")]
class PrincipalClasseStudentsMarksComponent extends Component
{
    use WireUiActions;

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


    public function render()
    {
        return view('livewire.tenants.users.pp.principal-classe-students-marks-component');
    }
}
