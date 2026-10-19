<?php

namespace App\Livewire\Tenants\Teachers;

use App\Livewire\Tenants\ActionsTraits\TeachersActions;
use App\Livewire\Tenants\ActionsTraits\TimePlanActions;
use App\Livewire\Tenants\ActionsTraits\UsersActions;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Services\TeachersServices\TeacherTimePlanService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Page profil Enseignant')]
class TeacherProfilPage extends Component
{
    use WireUiActions;
    use TeachersActions;
    use UsersActions;
    use TimePlanActions;

    public string $teacher_uuid;

    public $counter = 0;

    // Formulaire créneau (TimePlanActions)
    public bool $showSlotForm = false;
    public ?int $slotId = null;
    public ?int $slotFormPlanId = null;
    public ?int $assignment_id = null;
    public int $day_of_week = 1;
    public string $starts_at = '08:00';
    public string $ends_at = '10:00';
    public string $slot_label = '';
    public string $slot_notes = '';

    protected TeacherTimePlanService $teacherTimePlan;

    public function boot(TeacherTimePlanService $teacherTimePlan): void
    {
        $this->teacherTimePlan = $teacherTimePlan;
    }

    public function mount(string $teacher_uuid)
    {
        if (! $teacher_uuid) {
            return abort(404);
        }

        $teacher = Teacher::withTrashed()->where('uuid', $teacher_uuid)->first();

        if (! $teacher) {
            return abort(404);
        }

        $this->teacher_uuid = $teacher_uuid;
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function teacherHoursByClasse(): array
    {
        return $this->teacher->hoursByClasse();
    }

    #[Computed]
    public function teacher()
    {
        if (! $this->teacher_uuid) {
            return abort(404);
        }

        $teacher = Teacher::withTrashed()->where('uuid', $this->teacher_uuid)->first();

        if (! $teacher) {
            return abort(404);
        }

        return $teacher;
    }

    #[Computed]
    public function user()
    {
        if (! $this->teacher_uuid || ! $this->teacher) {
            return abort(404);
        }

        return $this->teacher->user;
    }

    /**
     * Créneaux de l’enseignant pour l’année active (cours + heures d’AP des matières).
     */
    #[Computed]
    public function teacherSlots(): Collection
    {
        if (! $this->teacher || ! $this->activeYear) {
            return collect();
        }

        return $this->teacherSlotsWithApFor($this->teacher->id, $this->activeYear->id);
    }

    /** Créneaux groupés par jour. */
    #[Computed]
    public function teacherSlotsByDay(): Collection
    {
        return $this->teacherTimePlan->groupSlotsByDay($this->teacherSlots);
    }

    /**
     * Affectations pour le formulaire : uniquement les classes / matières
     * où cet enseignant intervient (ClasseSubjectOfSchoolYear).
     */
    #[Computed]
    public function assignments(): Collection
    {
        if (! $this->teacher || ! $this->activeYear) {
            return collect();
        }

        return $this->assignmentsForTeacher($this->teacher->id, $this->activeYear->id);
    }

    public function slotFragmentAtTeacher(int $day, string $rowStart): ?object
    {
        return $this->teacherTimePlan->slotFragmentAt(
            $this->teacherSlotsByDay,
            $day,
            $rowStart,
            $this->scheduleRows(),
        );
    }

    public function isCellCoveredBySpanTeacher(int $day, string $rowStart): bool
    {
        return $this->teacherTimePlan->isCellCoveredBySpan(
            $this->teacherSlotsByDay,
            $day,
            $rowStart,
            $this->scheduleRows(),
        );
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata(): void
    {
        $this->counter++;
        $this->refreshPlansData();
    }

    public function render()
    {
        return view('livewire.tenants.teachers.teacher-profil-page');
    }
}
