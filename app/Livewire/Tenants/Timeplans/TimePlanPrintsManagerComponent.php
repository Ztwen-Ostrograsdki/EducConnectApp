<?php

namespace App\Livewire\Tenants\TimePlans;

use App\Jobs\JobToGeneratePrintableTimePlans;
use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TimePlan;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title('Impression des emplois du temps')]
#[Layout('livewire.layouts.tenant-auth-layout')]
class TimePlanPrintsManagerComponent extends Component
{
    use WireUiActions;

    /** classes | teachers | subject */
    public string $mode = 'classes';

    public ?int $filiar_id = null;
    public ?int $serial_id = null;
    public ?int $promotion_id = null;
    public ?int $classe_id = null;

    /** @var array<int> */
    public array $selected_classe_ids = [];

    /** @var array<int> */
    public array $selected_teacher_ids = [];

    public ?int $subject_id = null;

    /** published | draft | null (tous non archivés, published d’abord) */
    public string $plan_status = 'published';

    public function mount(): void
    {
        if (session()->has('print_timeplan_mode')) {
            $this->mode = session('print_timeplan_mode', 'classes');
        }

        $config = session('print_timeplan_config', []);
        $this->selected_classe_ids = $config['classe_ids'] ?? [];
        $this->selected_teacher_ids = $config['teacher_ids'] ?? [];
        $this->subject_id = $config['subject_id'] ?? null;
        $this->plan_status = $config['plan_status'] ?? 'published';
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function filiars(): Collection
    {
        return Filiar::query()->where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function serials(): Collection
    {
        return Serial::query()->where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function promotions(): Collection
    {
        return Promotion::query()->where('is_active', true)->orderByDesc('name')->get();
    }

    #[Computed]
    public function subjects(): Collection
    {
        return Subject::query()->where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function classes(): Collection
    {
        return Classe::query()
            ->where('school_year_id', $this->activeYear?->id)
            ->where('is_active', true)
            ->when($this->filiar_id, fn ($q) => $q->where('filiar_id', $this->filiar_id))
            ->when($this->serial_id, fn ($q) => $q->where('serial_id', $this->serial_id))
            ->when($this->promotion_id, fn ($q) => $q->where('promotion_id', $this->promotion_id))
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function teachers(): Collection
    {
        if ($this->mode === 'subject' && $this->subject_id) {
            $ids = ClasseSubjectOfSchoolYear::query()
                ->where('subject_id', $this->subject_id)
                ->where('school_year_id', $this->activeYear?->id)
                ->where('is_active', true)
                ->whereNull('ended_at')
                ->pluck('teacher_id')
                ->unique()
                ->filter();

            return Teacher::query()
                ->whereIn('id', $ids)
                ->with('user')
                ->get()
                ->sortBy(fn ($t) => $t->getFullName())
                ->values();
        }

        return Teacher::query()
            ->with('user')
            ->whereHas('classeSubjects', fn($qcl2) => 
                $qcl2->where('is_active', true)->where('school_year_id', $this->activeYear->id)->whereNull('ended_at')
            )
            ->get()
            ->sortBy(fn ($t) => $t->getFullName())
            ->values();
    }

    #[Computed]
    public function plansCount(): int
    {
        if ($this->mode === 'classes') {
            $ids = $this->selected_classe_ids ?: $this->classes->pluck('id')->all();

            return TimePlan::query()
                ->where('school_year_id', $this->activeYear?->id)
                ->whereIn('classe_id', $ids)
                ->where('status', '!=', 'archived')
                ->when($this->plan_status, fn ($q) => $q->where('status', $this->plan_status))
                ->count();
        }

        if ($this->mode === 'subject') {
            return $this->teachers->count();
        }

        return count($this->selected_teacher_ids);
    }

    #[Computed]
    public function docTitle(): string
    {
        $year = $this->activeYear?->slug ?? '';

        return match ($this->mode) {
            'subject' => 'EDT-matiere-' . ($this->subject_id ?? 'x') . '-' . $year,
            'teachers' => 'EDT-enseignants-' . $year,
            default => 'EDT-classes-' . $year,
        };
    }

    public function updatedMode(): void
    {
        $this->selected_classe_ids = [];
        $this->selected_teacher_ids = [];
        $this->subject_id = null;
        $this->persistSession();
    }

    public function updatedFiliarId(): void
    {
        $this->selected_classe_ids = [];
    }

    public function updatedSerialId(): void
    {
        $this->selected_classe_ids = [];
    }

    public function updatedPromotionId(): void
    {
        $this->selected_classe_ids = [];
    }

    public function updatedSubjectId(): void
    {
        $this->selected_teacher_ids = [];
        $this->persistSession();
    }

    public function toggleClasse(int $id): void
    {
        if (in_array($id, $this->selected_classe_ids, true)) {
            $this->selected_classe_ids = array_values(array_diff($this->selected_classe_ids, [$id]));
        } else {
            $this->selected_classe_ids[] = $id;
        }
        $this->persistSession();
    }

    public function selectAllClasses(): void
    {
        $this->selected_classe_ids = $this->classes->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->persistSession();
    }

    public function clearClasses(): void
    {
        $this->selected_classe_ids = [];
        $this->persistSession();
    }

    public function toggleTeacher(int $id): void
    {
        if (in_array($id, $this->selected_teacher_ids, true)) {
            $this->selected_teacher_ids = array_values(array_diff($this->selected_teacher_ids, [$id]));
        } else {
            $this->selected_teacher_ids[] = $id;
        }
        $this->persistSession();
    }

    public function selectAllTeachers(): void
    {
        $this->selected_teacher_ids = $this->teachers->pluck('id')->map(fn ($id) => (int) $id)->all();
        $this->persistSession();
    }

    public function clearTeachers(): void
    {
        $this->selected_teacher_ids = [];
        $this->persistSession();
    }

    public function resetFilters(): void
    {
        $this->mode = 'classes';
        $this->filiar_id = null;
        $this->serial_id = null;
        $this->promotion_id = null;
        $this->classe_id = null;
        $this->selected_classe_ids = [];
        $this->selected_teacher_ids = [];
        $this->subject_id = null;
        $this->plan_status = 'published';

        session()->forget([
            'print_timeplan_mode',
            'print_timeplan_config',
            'print_timeplan_school_year_id',
        ]);
    }

    protected function persistSession(): void
    {
        session()->put('print_timeplan_mode', $this->mode);
        session()->put('print_timeplan_school_year_id', $this->activeYear?->id);
        session()->put('print_timeplan_config', $this->buildConfig());
    }

    protected function buildConfig(): array
    {
        return [
            'classe_ids'  => $this->mode === 'classes'
                ? ($this->selected_classe_ids ?: $this->classes->pluck('id')->map(fn ($id) => (int) $id)->all())
                : [],
            'teacher_ids' => $this->mode === 'teachers' ? $this->selected_teacher_ids : [],
            'subject_id'  => $this->mode === 'subject' ? $this->subject_id : null,
            'plan_status' => $this->plan_status !== '' ? $this->plan_status : null,
        ];
    }

    public function initPrintProcess(): void
    {
        if (!$this->activeYear) {
            $this->notification()->error(title: 'Aucune année scolaire active.');
            return;
        }

        if ($this->mode === 'classes') {
            $ids = $this->selected_classe_ids ?: $this->classes->pluck('id')->all();
            if ($ids === []) {
                $this->notification()->info(title: 'Sélectionnez au moins une classe.');
                return;
            }
        }

        if ($this->mode === 'teachers' && $this->selected_teacher_ids === []) {
            $this->notification()->info(title: 'Sélectionnez au moins un enseignant.');
            return;
        }

        if ($this->mode === 'subject' && !$this->subject_id) {
            $this->notification()->info(title: 'Sélectionnez une matière.');
            return;
        }

        $this->persistSession();

        JobToGeneratePrintableTimePlans::dispatch(
            tenantId: tenant('id'),
            notifiableId: auth('tenant')->id(),
            mode: $this->mode,
            school_year_id: $this->activeYear->id,
            docTitle: $this->docTitle,
            config: $this->buildConfig(),

        );

        $this->notification()->success(
            title: 'Génération lancée',
            description: 'Le PDF des emplois du temps est en cours de génération. Vous serez notifié une fois prêt.',
        );
    }

    public function render()
    {
        return view('livewire.tenants.timeplans.time-plan-prints-manager-component');
    }
}
