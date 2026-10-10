<?php

namespace App\Livewire\Tenants\Classes\Sections;

use App\Livewire\Tenants\ActionsTraits\ClassesActions;
use App\Models\Classe;
use App\Models\SchoolYear;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ClasseMarksPage extends Component
{
    use WireUiActions, ClassesActions;

    public ?int $period = null;

    public ?string $classe_slug = null;

    public $counter = 0;

    public function mount()
    {
        $this->loadActivePeriod();
    }

    public function loadActivePeriod()
    {
        if($this->activeYear && $this->activeYear->is_active && $this->activeYear->active_period){

            $this->period = $this->activeYear->active_period;
        }

    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        unset($this->marksData, $this->studentsRows, $this->coef_relation);

        $this->counter++;
    }

    #[Computed]
    public function user()
    {
        return auth('tenant')->user();
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

    public function render()
    {
        return view('livewire.tenants.classes.sections.classe-marks-page');
    }
}
