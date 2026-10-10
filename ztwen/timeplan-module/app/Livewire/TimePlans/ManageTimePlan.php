<?php

namespace App\Livewire\TimePlans;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\TimePlan;
use App\Models\TimePlanSlot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;

class ManageTimePlan extends Component
{
    #[Locked]
    public ?int $timePlanId = null;

    public ?int $school_year_id = null;
    public ?int $classe_id = null;
    public string $title = '';
    public string $notes = '';

    public ?int $slotId = null;
    public ?int $assignment_id = null;
    public int $day_of_week = 1;
    public string $starts_at = '08:00';
    public string $ends_at = '10:00';
    public string $slot_label = '';
    public string $slot_notes = '';

    public string $search = '';
    public bool $showPlanForm = false;
    public bool $showSlotForm = false;

    public function mount(): void
    {
        $this->school_year_id = SchoolYear::query()->where('is_active', true)->orderByDesc('id')->value('id');
    }

    #[Computed]
    public function schoolYears()
    {
        return SchoolYear::query()->orderByDesc('min_year')->get();
    }

    #[Computed]
    public function classes()
    {
        if (!$this->school_year_id) return collect();

        return Classe::query()
            ->where('school_year_id', $this->school_year_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function plans()
    {
        return TimePlan::query()
            ->with(['classe', 'schoolYear'])
            ->when($this->school_year_id, fn (Builder $q) => $q->where('school_year_id', $this->school_year_id))
            ->when(trim($this->search) !== '', function (Builder $q) {
                $term = '%' . trim($this->search) . '%';
                $q->whereHas('classe', fn (Builder $classes) => $classes->where('name', 'like', $term));
            })
            ->withCount('slots')
            ->orderBy('classe_id')
            ->get();
    }

    #[Computed]
    public function currentPlan(): ?TimePlan
    {
        if (!$this->timePlanId) return null;

        return TimePlan::query()
            ->with(['classe', 'schoolYear', 'slots.classeSubjectOfSchoolYear.subject', 'slots.classeSubjectOfSchoolYear.teacher'])
            ->find($this->timePlanId);
    }

    #[Computed]
    public function assignments()
    {
        if (!$this->currentPlan) return collect();

        return ClasseSubjectOfSchoolYear::query()
            ->where('classe_id', $this->currentPlan->classe_id)
            ->where('school_year_id', $this->currentPlan->school_year_id)
            ->current()
            ->with(['subject', 'teacher'])
            ->get()
            ->filter(fn ($assignment) => $assignment->subject && $assignment->teacher)
            ->sortBy(fn ($assignment) => $assignment->subject->name)
            ->values();
    }

    public function updatedSchoolYearId(): void
    {
        $this->reset('classe_id', 'timePlanId', 'showSlotForm', 'showPlanForm');
        unset($this->classes, $this->plans, $this->currentPlan, $this->assignments);
    }

    public function openCreatePlan(): void
    {
        $this->authorizeDirectorAccess();
        $this->resetPlanForm();
        $this->showPlanForm = true;
    }

    public function editPlan(int $id): void
    {
        $this->authorizeDirectorAccess();
        $plan = TimePlan::query()->findOrFail($id);
        if (!$plan->isEditable()) {
            $this->dispatch('notify', type: 'error', message: 'Cet emploi du temps est archivé et ne peut plus être modifié.');
            return;
        }

        $this->timePlanId = $plan->id;
        $this->school_year_id = $plan->school_year_id;
        $this->classe_id = $plan->classe_id;
        $this->title = $plan->title ?? '';
        $this->notes = $plan->notes ?? '';
        $this->showPlanForm = true;
        unset($this->plans, $this->classes, $this->currentPlan);
    }

    public function savePlan(): void
    {
        $this->authorizeDirectorAccess();
        $data = $this->validate([
            'school_year_id' => ['required', 'integer', 'exists:tenant.school_years,id'],
            'classe_id' => ['required', 'integer', 'exists:tenant.classes,id'],
            'title' => ['nullable', 'string', 'max:150'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $year = SchoolYear::query()->findOrFail($data['school_year_id']);
        if ($year->is_closed) {
            throw ValidationException::withMessages(['school_year_id' => 'Cette année scolaire est clôturée.']);
        }

        $classe = Classe::query()->where('school_year_id', $year->id)->findOrFail($data['classe_id']);
        $exists = TimePlan::query()
            ->where('classe_id', $classe->id)
            ->where('school_year_id', $year->id)
            ->when($this->timePlanId, fn (Builder $q) => $q->where('id', '!=', $this->timePlanId))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages(['classe_id' => 'Un emploi du temps existe déjà pour cette classe et cette année scolaire.']);
        }

        $plan = DB::connection('tenant')->transaction(function () use ($data) {
            if ($this->timePlanId) {
                $plan = TimePlan::query()->lockForUpdate()->findOrFail($this->timePlanId);
                if (!$plan->isEditable()) throw ValidationException::withMessages(['title' => 'Cet emploi du temps est archivé.']);
                if ($plan->slots()->exists() && ((int) $plan->classe_id !== (int) $data['classe_id'] || (int) $plan->school_year_id !== (int) $data['school_year_id'])) {
                    throw ValidationException::withMessages(['classe_id' => 'La classe et l’année ne peuvent plus être changées après la création des créneaux. Supprime d’abord les créneaux si tu dois réellement déplacer cet emploi du temps.']);
                }
                $plan->update($data);
                return $plan;
            }

            return TimePlan::query()->create($data + ['status' => 'draft']);
        });

        $this->timePlanId = $plan->id;
        $this->showPlanForm = false;
        unset($this->plans, $this->currentPlan);
        $this->dispatch('notify', type: 'success', message: 'Emploi du temps enregistré.');
    }

    public function openPlan(int $id): void
    {
        $this->timePlanId = TimePlan::query()->findOrFail($id)->id;
        $this->showSlotForm = false;
        unset($this->currentPlan, $this->assignments);
    }

    public function openCreateSlot(): void
    {
        $this->authorizeDirectorAccess();
        abort_unless($this->currentPlan, 404);
        if (!$this->currentPlan->isEditable()) return;

        $this->resetSlotForm();
        $this->showSlotForm = true;
        unset($this->assignments);
    }

    public function editSlot(int $id): void
    {
        $this->authorizeDirectorAccess();
        $slot = TimePlanSlot::query()->where('time_plan_id', $this->timePlanId)->findOrFail($id);
        if (!$slot->timePlan->isEditable()) return;

        $this->slotId = $slot->id;
        $this->assignment_id = $slot->classe_subject_of_school_year_id;
        $this->day_of_week = $slot->day_of_week;
        $this->starts_at = substr((string) $slot->starts_at, 0, 5);
        $this->ends_at = substr((string) $slot->ends_at, 0, 5);
        $this->slot_label = $slot->label ?? '';
        $this->slot_notes = $slot->notes ?? '';
        $this->showSlotForm = true;
    }

    public function saveSlot(): void
    {
        $this->authorizeDirectorAccess();
        $plan = $this->currentPlan;
        abort_unless($plan, 404);
        if (!$plan->isEditable()) throw ValidationException::withMessages(['starts_at' => 'Cet emploi du temps est archivé.']);

        $data = $this->validate([
            'assignment_id' => ['required', 'integer'],
            'day_of_week' => ['required', 'integer', 'between:1,7'],
            'starts_at' => ['required', 'date_format:H:i'],
            'ends_at' => ['required', 'date_format:H:i', 'after:starts_at'],
            'slot_label' => ['nullable', 'string', 'max:150'],
            'slot_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $assignment = ClasseSubjectOfSchoolYear::query()
            ->whereKey($data['assignment_id'])
            ->where('classe_id', $plan->classe_id)
            ->where('school_year_id', $plan->school_year_id)
            ->current()
            ->first();

        if (!$assignment) {
            throw ValidationException::withMessages(['assignment_id' => 'Cette affectation n’est plus active pour cette classe et cette année scolaire.']);
        }

        DB::connection('tenant')->transaction(function () use ($plan, $assignment, $data) {
            $conflictInClass = TimePlanSlot::query()
                ->where('time_plan_id', $plan->id)
                ->where('day_of_week', $data['day_of_week'])
                ->where('id', '!=', $this->slotId ?? 0)
                ->where('starts_at', '<', $data['ends_at'])
                ->where('ends_at', '>', $data['starts_at'])
                ->exists();

            if ($conflictInClass) {
                throw ValidationException::withMessages(['starts_at' => 'Ce créneau chevauche déjà un autre cours de cette classe.']);
            }

            $teacherId = $assignment->teacher_id;
            $conflictForTeacher = TimePlanSlot::query()
                ->where('day_of_week', $data['day_of_week'])
                ->where('id', '!=', $this->slotId ?? 0)
                ->where('starts_at', '<', $data['ends_at'])
                ->where('ends_at', '>', $data['starts_at'])
                ->whereHas('timePlan', fn (Builder $q) => $q->where('school_year_id', $plan->school_year_id))
                ->whereHas('classeSubjectOfSchoolYear', fn (Builder $q) => $q->where('teacher_id', $teacherId)->whereNull('ended_at')->where('is_active', true))
                ->exists();

            if ($conflictForTeacher) {
                throw ValidationException::withMessages(['assignment_id' => 'Cet enseignant a déjà un cours sur ce créneau dans une autre classe.']);
            }

            $slot = $this->slotId
                ? TimePlanSlot::query()->where('time_plan_id', $plan->id)->findOrFail($this->slotId)
                : new TimePlanSlot(['time_plan_id' => $plan->id]);

            $slot->fill([
                'classe_subject_of_school_year_id' => $assignment->id,
                'day_of_week' => $data['day_of_week'],
                'starts_at' => $data['starts_at'],
                'ends_at' => $data['ends_at'],
                'label' => $data['slot_label'] ?: null,
                'notes' => $data['slot_notes'] ?: null,
            ]);
            $slot->save();
        });

        $this->showSlotForm = false;
        $this->resetSlotForm();
        unset($this->currentPlan, $this->assignments, $this->plans);
        $this->dispatch('notify', type: 'success', message: 'Créneau enregistré.');
    }

    public function deleteSlot(int $id): void
    {
        $this->authorizeDirectorAccess();
        $plan = $this->currentPlan;
        abort_unless($plan && $plan->isEditable(), 404);
        TimePlanSlot::query()->where('time_plan_id', $plan->id)->findOrFail($id)->delete();
        unset($this->currentPlan, $this->plans);
        $this->dispatch('notify', type: 'success', message: 'Créneau supprimé.');
    }

    public function publishPlan(): void
    {
        $this->authorizeDirectorAccess();
        $plan = $this->currentPlan;
        abort_unless($plan, 404);
        if ($plan->status === 'archived') return;
        if (!$plan->slots()->exists()) {
            throw ValidationException::withMessages(['title' => 'Ajoute au moins un créneau avant de publier cet emploi du temps.']);
        }
        $plan->update(['status' => 'published', 'published_at' => now()]);
        unset($this->currentPlan, $this->plans);
        $this->dispatch('notify', type: 'success', message: 'Emploi du temps publié.');
    }

    public function archivePlan(): void
    {
        $this->authorizeDirectorAccess();
        $plan = $this->currentPlan;
        abort_unless($plan, 404);
        $plan->update(['status' => 'archived']);
        unset($this->currentPlan, $this->plans);
        $this->dispatch('notify', type: 'success', message: 'Emploi du temps archivé.');
    }

    private function resetPlanForm(): void
    {
        $this->reset('timePlanId', 'classe_id', 'title', 'notes');
        $this->school_year_id ??= SchoolYear::query()->where('is_active', true)->orderByDesc('id')->value('id');
    }

    private function resetSlotForm(): void
    {
        $this->reset('slotId', 'assignment_id', 'slot_label', 'slot_notes');
        $this->day_of_week = 1;
        $this->starts_at = '08:00';
        $this->ends_at = '10:00';
    }

    /**
     * À remplacer par la Policy/autorisation déjà utilisée par EducConnect
     * si les routes sont accessibles à plusieurs rôles.
     */
    private function authorizeDirectorAccess(): void
    {
        $user = auth()->user();
        abort_unless($user && method_exists($user, 'hasRole') && $user->hasRole('directeur'), 403);
    }

    public function render()
    {
        return view('livewire.time-plans.manage-time-plan');
    }
}
