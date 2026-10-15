<?php

namespace App\Livewire\Tenants\Components;

use App\Livewire\Tenants\ActionsTraits\TimePlanActions;
use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\TimePlan;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ClasseTimePlanComponent extends Component
{
    use WireUiActions, TimePlanActions;

    public $counter = 0;

    public ?string $classe_slug = '';
    public ?int $classeId;

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata(): void
    {
        $this->counter++;
        $this->refreshPlansData();
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

        if (!$classe) {
            return abort(404);
        }

        return $classe;
    }

    #[Computed]
    public function timePlan()
    {
        if (!$this->activeYear || !$this->classe) {
            return null;
        }

        return TimePlan::query()
            ->where('classe_id', $this->classe->id)
            ->where('school_year_id', $this->activeYear->id)
            ->with([
                'schoolYear',
                'slots' => fn ($query) => $query->orderBy('starts_at'),
                'slots.classeSubjectOfSchoolYear.subject',
                'slots.classeSubjectOfSchoolYear.teacher',
            ])
            ->first();
    }

    public function render()
    {
        return view('livewire.tenants.components.classe-time-plan-component');
    }
}
