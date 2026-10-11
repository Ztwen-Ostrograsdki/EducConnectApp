<?php

namespace App\Livewire\Tenants\Subjects;

use App\Livewire\Tenants\ActionsTraits\SubjectAnimationTimesActions;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\SubjectAnimationTime;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Gestion des heures AP d'une matière")]
class ManageSubjectAPTimeComponent extends Component
{
    use SubjectAnimationTimesActions;
    use WithPagination;

    public int $perPage = 15;

    public ?string $search = null;

    /** Filtre : actives | inactives | all */
    public string $is_active_filter = 'actives';

    public ?int $subject_id = null;
    public ?string $subject_slug = null;
    public ?Subject $subject = null;

    // ---- Formulaire ----
    public bool $showAPTimeForm = false;
    public ?int $ap_time_id = null;
    public ?int $day_of_week = 1;
    public ?string $starts_at = '08:00';
    public ?string $ends_at = '10:00';
    public ?string $label = 'AP';
    public ?string $notes = 'Animation Pédagogique Hebdomadaire (AP-H)';
    public bool $is_active = true;

    public function mount(?string $subject_slug = null): void
    {
        if ($subject_slug) {
            $this->subject_slug = $subject_slug;

            $subject = Subject::withTrashed()->whereSlug($subject_slug)->first();

            if (!$subject) {
                abort(404);
            }

            $this->subject    = $subject;
            $this->subject_id = $subject->id;
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedIsActiveFilter(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function subjects()
    {
        return Subject::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function apTimes()
    {
        return $this->apTimesQuery()
            ->paginate($this->perPage);
    }

    #[Computed]
    public function activesCount(): int
    {
        $year = $this->activeYear;

        return SubjectAnimationTime::query()
            ->when($year, fn ($q) => $q->where('school_year_id', $year->id))
            ->when($this->subject_id, fn ($q) => $q->where('subject_id', $this->subject_id))
            ->where('is_active', true)
            ->count();
    }

    #[Computed]
    public function inactivesCount(): int
    {
        $year = $this->activeYear;

        return SubjectAnimationTime::query()
            ->when($year, fn ($q) => $q->where('school_year_id', $year->id))
            ->when($this->subject_id, fn ($q) => $q->where('subject_id', $this->subject_id))
            ->where('is_active', false)
            ->count();
    }

    public function render()
    {
        return view('livewire.tenants.subjects.manage-subject-a-p-time-component');
    }
}
