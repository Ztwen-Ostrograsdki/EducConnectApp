<?php

namespace App\Livewire\Tenants\Timeplans;

use App\Livewire\Tenants\ActionsTraits\TimePlanActions;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\TimePlan;
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

    #[Url(as: 'q')]
    public string $search = '';

    /** @var string filtre : all|classe|filiar|promotion|serial|is_new_system */
    #[Url(as: 'filter')]
    public string $filterBy = 'all';

    #[Url(as: 'filter_value')]
    public string|int|null $filterValue = null;

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
    //  COMPUTED (spécifiques dashboard)
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
        $planId = $this->slotFormPlanId ?? $this->timePlanId;

        return $planId ? $this->assignmentsFor($planId) : collect();
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
    //  PLAN CRUD (spécifique dashboard)
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
        if(session('current_time_plan') && $this->currentPlan){

            if(session('current_time_plan') == $id || $this->currentPlan->id == $id){

                session()->forget('current_time_plan');

                $this->reset('timePlanId');

                $this->showSlotForm = false;

                unset($this->currentPlan, $this->assignments);

                return;
            }

        }

        $timePlan = TimePlan::query()->find($id);

        if (!$timePlan) {
            return;
        }

        session()->put('current_time_plan', $id);
        $this->timePlanId = $timePlan->id;
        $this->showSlotForm = false;

        unset($this->currentPlan, $this->assignments);
    }

    private function resetPlanForm(): void
    {
        $this->reset('timePlanId', 'classe_id', 'title', 'notes', 'status');
        $this->status = 'draft';
        $this->school_year_id ??= SchoolYear::query()
            ->where('is_active', true)
            ->orderByDesc('id')
            ->value('id');
    }

    public function render()
    {
        return view('livewire.tenants.timeplans.time-plan-dashboard');
    }
}
