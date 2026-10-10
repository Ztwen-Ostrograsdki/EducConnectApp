<?php

namespace App\Livewire\Tenants\Classes\Sections;

use App\Livewire\Tenants\ActionsTraits\ClassesActions;
use App\Livewire\Tenants\ActionsTraits\TimePlanActions;
use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\TimePlan;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ClassePlanPage extends Component
{
    use WireUiActions;
    use ClassesActions;
    use TimePlanActions;

    public ?int $period = null;
    public ?string $classe_slug = null;
    public $counter = 0;

    // Formulaire créneau (requis par TimePlanActions)
    public bool $showSlotForm = false;
    public ?int $slotId = null;
    public ?int $slotFormPlanId = null;
    public ?int $assignment_id = null;
    public int $day_of_week = 1;
    public string $starts_at = '08:00';
    public string $ends_at = '10:00';
    public string $slot_label = '';
    public string $slot_notes = '';

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

    /** Affectations pour le formulaire créneau (plan en cours d’édition). */
    #[Computed]
    public function assignments()
    {
        $planId = $this->slotFormPlanId ?? $this->timePlan?->id;

        return $planId ? $this->assignmentsFor($planId) : collect();
    }

    public function render()
    {
        return view('livewire.tenants.classes.sections.classe-plan-page');
    }
}
