<?php

namespace App\Livewire\Tenants\Epreuves;

use App\Livewire\Tenants\ActionsTraits\EpreuveActionsTrait;
use App\Models\Classe;
use App\Models\Epreuve;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Subject;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Title('Gestion des épreuves')]
#[Layout('livewire.layouts.tenant-auth-layout')]
/**
 * @property-read LengthAwarePaginator $epreuves
 */
class ManageEpreuves extends Component
{
    use WithPagination, WireUiActions, EpreuveActionsTrait;

    /** @var array<int> */
    public array $selected = [];

    #[Url]
    public string $search = '';

    #[Url]
    public ?int $school_year_id = null;

    #[Url]
    public ?int $classe_id = null;

    #[Url]
    public ?int $filiar_id = null;

    #[Url]
    public ?int $promotion_id = null;

    #[Url]
    public ?int $subject_id = null;

    #[Url]
    public ?int $period = null;

    #[Url]
    public ?string $type = null;

    #[Url]
    public ?string $teacher_name = null;

    #[Url]
    public ?string $published = null; // '1' | '0' | null

    #[Url]
    public ?string $locked = null; // '1' | '0' | null

    public int $perPage = 15;

    protected function canManageAllEpreuves(): bool
    {
        return true; // Directeur
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSchoolYearId(): void
    {
        $this->resetPage();
    }

    public function updatedClasseId(): void
    {
        $this->resetPage();
    }

    public function updatedFiliarId(): void
    {
        $this->resetPage();
    }

    public function updatedPromotionId(): void
    {
        $this->resetPage();
    }

    public function updatedSubjectId(): void
    {
        $this->resetPage();
    }

    public function updatedPeriod(): void
    {
        $this->resetPage();
    }

    public function updatedType(): void
    {
        $this->resetPage();
    }

    public function updatedTeacherName(): void
    {
        $this->resetPage();
    }

    public function updatedPublished(): void
    {
        $this->resetPage();
    }

    public function updatedLocked(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'school_year_id',
            'classe_id',
            'filiar_id',
            'promotion_id',
            'subject_id',
            'period',
            'type',
            'teacher_name',
            'published',
            'locked',
        ]);
        $this->resetPage();
    }

    public function toggleSelectAll(): void
    {
        $ids = $this->epreuves->pluck('id')->map(fn ($id) => (int) $id)->all();

        if (count(array_intersect($this->selected, $ids)) === count($ids) && count($ids) > 0) {
            $this->selected = array_values(array_diff($this->selected, $ids));
        } else {
            $this->selected = array_values(array_unique(array_merge($this->selected, $ids)));
        }
    }

    #[Computed]
    public function epreuves()
    {
        $query = Epreuve::query()
            ->with(['subject:id,name', 'classe:id,name', 'filiar:id,name', 'promotion:id,name', 'schoolYear:id,slug,min_year,max_year'])
            ->latest();

        if ($this->school_year_id) {
            $query->where('school_year_id', $this->school_year_id);
        }

        if ($this->classe_id) {
            $query->where('classe_id', $this->classe_id);
        }

        if ($this->filiar_id) {
            $query->where('filiar_id', $this->filiar_id);
        }

        if ($this->promotion_id) {
            $query->where('promotion_id', $this->promotion_id);
        }

        if ($this->subject_id) {
            $query->where('subject_id', $this->subject_id);
        }

        if ($this->period) {
            $query->where('period', $this->period);
        }

        if ($this->type) {
            $query->where('type', 'like', '%' . $this->type . '%');
        }

        if ($this->teacher_name) {
            $query->where('teacher_name', 'like', '%' . $this->teacher_name . '%');
        }

        if ($this->published === '1') {
            $query->where('published', true);
        } elseif ($this->published === '0') {
            $query->where('published', false);
        }

        if ($this->locked === '1') {
            $query->where('locked', true);
        } elseif ($this->locked === '0') {
            $query->where('locked', false);
        }

        if (filled($this->search)) {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('filename', 'like', $term)
                    ->orWhere('classe_name', 'like', $term)
                    ->orWhere('teacher_name', 'like', $term)
                    ->orWhere('type', 'like', $term)
                    ->orWhere('period_as_label', 'like', $term)
                    ->orWhereHas('filiar', fn ($f) => $f->where('name', 'like', $term))
                    ->orWhereHas('promotion', fn ($p) => $p->where('name', 'like', $term))
                    ->orWhereHas('subject', fn ($s) => $s->where('name', 'like', $term))
                    ->orWhereHas('classe', fn ($c) => $c->where('name', 'like', $term));
            });
        }

        return $query->paginate($this->perPage);
    }

    #[Computed]
    public function schoolYears()
    {
        return SchoolYear::query()->orderByDesc('min_year')->get(['id', 'slug', 'min_year', 'max_year']);
    }

    #[Computed]
    public function classes()
    {
        $q = Classe::query()->orderBy('name');

        if ($this->school_year_id) {
            $q->where('school_year_id', $this->school_year_id);
        }

        return $q->get(['id', 'name']);
    }

    #[Computed]
    public function filiars()
    {
        return Filiar::query()->orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function promotions()
    {
        return Promotion::query()->orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function subjects()
    {
        return Subject::query()->orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function periods(): array
    {
        $year = $this->school_year_id
            ? SchoolYear::find($this->school_year_id)
            : SchoolYear::query()->current()->first();

        return $year ? $year->getPeriods() : [];
    }

    #[Computed]
    public function teacherNames()
    {
        return Epreuve::query()
            ->whereNotNull('teacher_name')
            ->distinct()
            ->orderBy('teacher_name')
            ->pluck('teacher_name');
    }

    public function render()
    {

        return view('livewire.tenants.epreuves.manage-epreuves');
    }
}
