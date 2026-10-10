<?php

namespace App\Livewire\Tenants\Classes\Sections;

use App\Livewire\Tenants\ActionsTraits\ClassesActions;
use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\TimePlan;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ClassePlanPage extends Component
{


    use WireUiActions, ClassesActions;

    public ?int $period = null;

    public ?string $classe_slug = null;

    public $counter = 0;

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

    protected function loadTimetable(): ?TimePlan
    {
        if (! $this->activeYear) {
            return null;
        }

        // Never expose draft/archived plans on the class profile.
        return TimePlan::query()
            ->where('classe_id', $this->classe->id)
            ->where('school_year_id', $this->activeYear->id)
            // ->where('status', 'published')
            ->with([
                'schoolYear',
                'slots' => fn ($query) => $query->orderBy('starts_at'),
                'slots.classeSubjectOfSchoolYear.subject',
                'slots.classeSubjectOfSchoolYear.teacher',
            ])
            ->first();
    }

    #[Computed]
    public function days()
    {
        return [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi'];
    }
    
    #[Computed]
    public function timePlan()
    {
        return $this->loadTimetable();
    }
    
    #[Computed]
    public function slotsByDay()
    {
        return collect($this->days)->mapWithKeys(fn ($label, $day) => [
            $day => $this->timePlan?->slots->where('day_of_week', $day)->values() ?? collect(),
        ]);
    }

    #[Computed]
    public function timeRanges()
    {
        

        return $this->timePlan?->slots
            ->map(fn ($slot) => [$slot->starts_at, $slot->ends_at])
            ->unique(fn ($range) => $range[0].'|'.$range[1])
            ->sortBy(fn ($range) => $range[0])
            ->values() ?? collect();

    }


    public function render()
    {
        return view('livewire.tenants.classes.sections.classe-plan-page');
    }
}
