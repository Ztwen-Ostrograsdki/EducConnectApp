<?php

namespace App\Livewire\Tenants\Timeplans;

use App\Livewire\Tenants\ActionsTraits\TimePlanActions;
use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\TimePlan;
use App\Models\TimePlanSlot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Les emplois du temps')]
class TimePlanDashboard extends Component
{
    use WireUiActions;
    use WithPagination;
    use TimePlanActions;

    #[Locked]
    public ?int $timePlanId = null;

    public ?int $school_year_id = null;
    public ?int $classe_id = null;
    public string $title = '';
    public string $notes = '';
    public string $status = 'draft';

    public ?int $slotId = null;
    public ?int $assignment_id = null;
    public int $day_of_week = 1;
    public string $starts_at = '08:00';
    public string $ends_at = '10:00';
    public string $slot_label = '';
    public string $slot_notes = '';

    #[Url(as: 'q')]
    public string $search = '';

    /** @var string filtre principal : all|classe|filiar|promotion|serial|is_new_system */
    #[Url(as: 'filter')]
    public string $filterBy = 'all';

    /** @var string|int|null valeur du filtre (id ou 0/1 pour is_new_system) */
    #[Url(as: 'filter_value')]
    public string|int|null $filterValue = null;

    public bool $showPlanForm = false;
    public bool $showSlotForm = false;

    public int $perPage = 12;

    public function mount(): void
    {
        $this->school_year_id = SchoolYear::query()
            ->where('is_active', true)
            ->orderByDesc('id')
            ->value('id');

        if (session()->has('current_time_plan')) {
            $this->timePlanId = session('current_time_plan');
            $this->showSlotForm = false;
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  COMPUTED
    // ─────────────────────────────────────────────────────────────

    #[Computed]
    public function schoolYears()
    {
        return SchoolYear::query()->orderByDesc('min_year')->get();
    }

    #[Computed]
    public function classes()
    {
        if (!$this->school_year_id) {
            return collect();
        }

        return Classe::query()
            ->where('school_year_id', $this->school_year_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function filiars()
    {
        return Filiar::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
    }

    #[Computed]
    public function promotions()
    {
        return Promotion::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'level']);
    }

    #[Computed]
    public function serials()
    {
        return Serial::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);
    }

    #[Computed]
    public function plans()
    {
        return TimePlan::query()
            ->with(['classe.filiar', 'classe.promotion', 'classe.serial', 'schoolYear'])
            ->when($this->school_year_id, fn (Builder $q) => $q->where('school_year_id', $this->school_year_id))
            ->when(trim($this->search) !== '', function (Builder $q) {
                $term = '%' . trim($this->search) . '%';
                $q->where(function (Builder $inner) use ($term) {
                    $inner->whereHas('classe', fn (Builder $c) => $c->where('name', 'like', $term))
                        ->orWhere('title', 'like', $term);
                });
            })
            ->when($this->filterBy !== 'all' && filled($this->filterValue), function (Builder $q) {
                match ($this->filterBy) {
                    'classe' => $q->where('classe_id', (int) $this->filterValue),
                    'filiar' => $q->whereHas('classe', fn (Builder $c) => $c->where('filiar_id', (int) $this->filterValue)),
                    'promotion' => $q->whereHas('classe', fn (Builder $c) => $c->where('promotion_id', (int) $this->filterValue)),
                    'serial' => $q->whereHas('classe', fn (Builder $c) => $c->where('serial_id', (int) $this->filterValue)),
                    'is_new_system' => $q->whereHas('classe', fn (Builder $c) => $c->where('is_new_system', (bool) $this->filterValue)),
                    default => null,
                };
            })
            ->withCount('slots')
            ->orderBy('classe_id')
            ->paginate($this->perPage);
    }

    #[Computed]
    public function currentPlan(): ?TimePlan
    {
        if (!$this->timePlanId) {
            return null;
        }

        return TimePlan::query()
            ->with([
                'classe',
                'schoolYear',
                'slots.classeSubjectOfSchoolYear.subject',
                'slots.classeSubjectOfSchoolYear.teacher',
            ])
            ->find($this->timePlanId);
    }

    #[Computed]
    public function assignments()
    {
        if (!$this->currentPlan) {
            return collect();
        }

        return ClasseSubjectOfSchoolYear::query()
            ->where('classe_id', $this->currentPlan->classe_id)
            ->where('school_year_id', $this->currentPlan->school_year_id)
            ->whereDoesntHave('subject', fn ($q) => $q->whereIn('name', ['conduite', 'Conduite']))
            ->current()
            ->with(['subject', 'teacher'])
            ->get()
            ->filter(fn ($assignment) => $assignment->subject && $assignment->teacher)
            ->sortBy(fn ($assignment) => $assignment->subject->name)
            ->values();
    }

    // ─────────────────────────────────────────────────────────────
    //  HOOKS
    // ─────────────────────────────────────────────────────────────

    public function updatedSchoolYearId(): void
    {
        $this->resetPage();
        $this->reset('classe_id', 'timePlanId', 'showSlotForm', 'showPlanForm', 'filterBy', 'filterValue');
        unset($this->classes, $this->plans, $this->currentPlan, $this->assignments);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilterBy(): void
    {
        $this->filterValue = null;
        $this->resetPage();
        unset($this->plans);
    }

    public function updatedFilterValue(): void
    {
        $this->resetPage();
        unset($this->plans);
    }

    // ─────────────────────────────────────────────────────────────
    //  PLAN CRUD
    // ─────────────────────────────────────────────────────────────

    public function openCreatePlan(): void
    {
        $this->resetPlanForm();
        $this->showPlanForm = true;
    }

    public function editPlan(int $id): void
    {
        $plan = TimePlan::query()->find($id);

        if (!$plan) {
            $this->notification()->error(
                title: 'Emploi du temps introuvable',
                description: 'Cet emploi du temps n’existe pas ou a été supprimé.',
            );
            return;
        }

        $this->timePlanId = $plan->id;
        $this->status = $plan->status;
        $this->school_year_id = $plan->school_year_id;
        $this->classe_id = $plan->classe_id;
        $this->title = $plan->title ?? '';
        $this->notes = $plan->notes ?? '';
        $this->showPlanForm = true;

        unset($this->plans, $this->classes, $this->currentPlan);
    }

    public function savePlan(): void
    {
        $data = $this->validate([
            'school_year_id' => ['required', 'integer', 'exists:tenant.school_years,id'],
            'classe_id'      => ['required', 'integer', 'exists:tenant.classes,id'],
            'title'          => ['nullable', 'string', 'max:150'],
            'notes'          => ['nullable', 'string', 'max:5000'],
            'status'         => ['required', 'string'],
        ]);

        $year = SchoolYear::query()->findOrFail($data['school_year_id']);

        if ($year->is_closed) {
            $this->notification()->error(
                title: 'Année clôturée',
                description: "L'année scolaire {$year->slug} est clôturée.",
            );
            throw ValidationException::withMessages(['school_year_id' => 'Cette année scolaire est clôturée.']);
        }

        $classe = Classe::find($data['classe_id']);

        $exists = TimePlan::query()
            ->where('classe_id', $classe->id)
            ->where('school_year_id', $year->id)
            ->when($this->timePlanId, fn (Builder $q) => $q->where('id', '!=', $this->timePlanId))
            ->exists();

        if ($exists) {
            $this->notification()->error(
                title: 'Doublon détecté',
                description: "Un emploi du temps existe déjà pour cette classe et l'année scolaire {$year->slug}.",
            );
            throw ValidationException::withMessages(['classe_id' => 'Un emploi du temps existe déjà pour cette classe et cette année scolaire.']);
        }

        $plan = DB::connection('tenant')->transaction(function () use ($data) {
            if ($this->timePlanId) {
                $plan = TimePlan::query()->lockForUpdate()->findOrFail($this->timePlanId);

                if (
                    $plan->slots()->exists()
                    && (
                        (int) $plan->classe_id !== (int) $data['classe_id']
                        || (int) $plan->school_year_id !== (int) $data['school_year_id']
                    )
                ) {
                    $this->notification()->error(
                        title: 'Modification interdite',
                        description: 'La classe et l’année ne peuvent plus être changées après la création des créneaux.',
                    );
                    throw ValidationException::withMessages([
                        'classe_id' => 'Supprimez d’abord les créneaux pour déplacer cet emploi du temps.',
                    ]);
                }

                $plan->update($data);

                if ($this->status !== 'published') {
                    $plan->update(['published_at' => null]);
                } elseif ($this->status === 'published' && !$plan->published_at) {
                    $plan->update(['status' => 'published', 'published_at' => now()]);
                }

                return $plan;
            }

            return TimePlan::query()->create($data + ['status' => 'draft']);
        });

        $this->timePlanId = $plan->id;
        $this->showPlanForm = false;
        unset($this->plans, $this->currentPlan);

        $this->notification()->success(
            title: 'Emploi du temps enregistré',
            description: "L'emploi du temps a été enregistré avec succès.",
        );
    }

    public function openPlan(int $id): void
    {
        $timePlan = TimePlan::query()->find($id);

        if (!$timePlan) {
            return;
        }

        session()->put('current_time_plan', $id);
        $this->timePlanId = $timePlan->id;
        $this->showSlotForm = false;

        unset($this->currentPlan, $this->assignments);
    }

    // ─────────────────────────────────────────────────────────────
    //  SLOT CRUD
    // ─────────────────────────────────────────────────────────────

    public function openCreateSlot(): void
    {
        abort_unless($this->currentPlan, 404);

        if (!$this->currentPlan->isEditable()) {
            return;
        }

        $this->resetSlotForm();
        $this->showSlotForm = true;
        unset($this->assignments);
    }

    public function editSlot(int $id): void
    {
        $slot = TimePlanSlot::query()
            ->where('time_plan_id', $this->timePlanId)
            ->findOrFail($id);

        if (!$slot->timePlan->isEditable()) {
            return;
        }

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
        $plan = $this->currentPlan;
        abort_unless($plan, 404);

        if (!$plan->isEditable()) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Cet emploi du temps est archivé.',
            );
            throw ValidationException::withMessages(['starts_at' => 'Cet emploi du temps est archivé.']);
        }

        $data = $this->validate([
            'assignment_id' => ['required', 'integer'],
            'day_of_week'   => ['required', 'integer', 'between:1,7'],
            'starts_at'     => ['required', 'date_format:H:i'],
            'ends_at'       => ['required', 'date_format:H:i', 'after:starts_at'],
            'slot_label'    => ['nullable', 'string', 'max:150'],
            'slot_notes'    => ['nullable', 'string', 'max:2000'],
        ]);

        $assignment = ClasseSubjectOfSchoolYear::query()
            ->whereKey($data['assignment_id'])
            ->where('classe_id', $plan->classe_id)
            ->where('school_year_id', $plan->school_year_id)
            ->current()
            ->first();

        if (!$assignment) {
            $this->notification()->error(
                title: 'Matière indisponible',
                description: 'Cette matière n’est plus valable pour cette classe et cette année scolaire.',
            );
            throw ValidationException::withMessages([
                'assignment_id' => 'Cette affectation n’est plus active pour cette classe et cette année scolaire.',
            ]);
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
                $this->notification()->error(
                    title: 'Chevauchement horaire',
                    description: 'Ce créneau chevauche déjà un autre cours de cette classe.',
                );
                throw ValidationException::withMessages(['starts_at' => 'Ce créneau chevauche déjà un autre cours de cette classe.']);
            }

            $teacherId = $assignment->teacher_id;

            $conflictForTeacher = TimePlanSlot::query()
                ->where('day_of_week', $data['day_of_week'])
                ->where('id', '!=', $this->slotId ?? 0)
                ->where('starts_at', '<', $data['ends_at'])
                ->where('ends_at', '>', $data['starts_at'])
                ->whereHas('timePlan', fn (Builder $q) => $q->where('school_year_id', $plan->school_year_id))
                ->whereHas(
                    'classeSubjectOfSchoolYear',
                    fn (Builder $q) => $q
                        ->where('teacher_id', $teacherId)
                        ->whereNull('ended_at')
                        ->where('is_active', true)
                )
                ->exists();

            if ($conflictForTeacher) {
                $this->notification()->error(
                    title: 'Conflit enseignant',
                    description: 'Cet enseignant a déjà un cours sur ce créneau dans une autre classe.',
                );
                throw ValidationException::withMessages([
                    'assignment_id' => 'Cet enseignant a déjà un cours sur ce créneau dans une autre classe.',
                ]);
            }

            $slot = $this->slotId
                ? TimePlanSlot::query()->where('time_plan_id', $plan->id)->findOrFail($this->slotId)
                : new TimePlanSlot(['time_plan_id' => $plan->id]);

            $slot->fill([
                'classe_subject_of_school_year_id' => $assignment->id,
                'day_of_week'                      => $data['day_of_week'],
                'starts_at'                        => $data['starts_at'],
                'ends_at'                          => $data['ends_at'],
                'label'                            => $data['slot_label'] ?: null,
                'notes'                            => $data['slot_notes'] ?: null,
            ]);
            $slot->save();
        });

        $this->showSlotForm = false;
        $this->resetSlotForm();
        unset($this->currentPlan, $this->assignments, $this->plans);

        $this->notification()->success(
            title: 'Créneau enregistré',
            description: 'Le créneau a été enregistré avec succès.',
        );
    }

    // ─────────────────────────────────────────────────────────────
    //  HELPERS PRIVÉS
    // ─────────────────────────────────────────────────────────────

    private function resetPlanForm(): void
    {
        $this->reset('timePlanId', 'classe_id', 'title', 'notes', 'status');
        $this->status = 'draft';
        $this->school_year_id ??= SchoolYear::query()
            ->where('is_active', true)
            ->orderByDesc('id')
            ->value('id');
    }

    private function resetSlotForm(): void
    {
        $this->reset('slotId', 'assignment_id', 'slot_label', 'slot_notes');
        $this->day_of_week = 1;
        $this->starts_at = '08:00';
        $this->ends_at = '10:00';
    }

    public function render()
    {
        return view('livewire.tenants.timeplans.time-plan-dashboard');
    }
}
