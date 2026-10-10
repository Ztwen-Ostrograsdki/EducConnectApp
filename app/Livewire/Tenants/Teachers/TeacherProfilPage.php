<?php

namespace App\Livewire\Tenants\Teachers;

use App\Livewire\Tenants\ActionsTraits\TeachersActions;
use App\Livewire\Tenants\ActionsTraits\TimePlanActions;
use App\Livewire\Tenants\ActionsTraits\UsersActions;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\TimePlanSlot;
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

    public function mount(string $teacher_uuid)
    {
        if (!$teacher_uuid) {
            return abort(404);
        }

        $teacher = Teacher::withTrashed()->where('uuid', $teacher_uuid)->first();

        if (!$teacher) {
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
    public function teacher()
    {
        if (!$this->teacher_uuid) {
            return abort(404);
        }

        $teacher = Teacher::withTrashed()->where('uuid', $this->teacher_uuid)->first();

        if (!$teacher) {
            return abort(404);
        }

        return $teacher;
    }

    #[Computed]
    public function user()
    {
        if (!$this->teacher_uuid || !$this->teacher) {
            return abort(404);
        }

        return $this->teacher->user;
    }

    /** Créneaux de l’enseignant pour l’année active. */
    #[Computed]
    public function teacherSlots(): Collection
    {
        if (!$this->teacher || !$this->activeYear) {
            return collect();
        }

        return $this->teacherSlotsFor($this->teacher->id, $this->activeYear->id);
    }

    /** Créneaux groupés par jour. */
    #[Computed]
    public function teacherSlotsByDay(): Collection
    {
        return $this->slotsByDayFromSlots($this->teacherSlots);
    }

    /**
     * Affectations pour le formulaire : uniquement les classes / matières
     * où cet enseignant intervient (ClasseSubjectOfSchoolYear).
     */
    #[Computed]
    public function assignments(): Collection
    {
        if (!$this->teacher || !$this->activeYear) {
            return collect();
        }

        return $this->assignmentsForTeacher($this->teacher->id, $this->activeYear->id);
    }

    public function slotFragmentAtTeacher(int $day, string $rowStart): ?TimePlanSlot
    {
        $daySlots = $this->teacherSlotsByDay[$day] ?? collect();

        $starting = $daySlots->first(
            fn (TimePlanSlot $slot) => $this->normalizeTime((string) $slot->starts_at) === $rowStart
        );

        if ($starting) {
            return $starting;
        }

        $idx = $this->scheduleHourIndex($rowStart);
        if ($idx <= 0) {
            return null;
        }

        $rows = $this->scheduleRows();
        $prev = $rows[$idx - 1] ?? null;
        if (!$prev || ($prev['type'] ?? '') !== 'break') {
            return null;
        }

        return $daySlots->first(function (TimePlanSlot $slot) use ($rowStart) {
            $slotStart = $this->normalizeTime((string) $slot->starts_at);
            $slotEnd = $this->normalizeTime((string) $slot->ends_at);

            return $slotStart < $rowStart && $slotEnd > $rowStart;
        });
    }

    public function isCellCoveredBySpanTeacher(int $day, string $rowStart): bool
    {
        $idx = $this->scheduleHourIndex($rowStart);
        if ($idx < 0) {
            return false;
        }

        $rows = $this->scheduleRows();
        $daySlots = $this->teacherSlotsByDay[$day] ?? collect();

        foreach ($daySlots as $slot) {
            $slotStart = $this->normalizeTime((string) $slot->starts_at);
            $slotEnd = $this->normalizeTime((string) $slot->ends_at);

            if (!($slotStart < $rowStart && $slotEnd > $rowStart)) {
                continue;
            }

            $fragmentStartIdx = $this->scheduleHourIndex($slotStart);
            if ($fragmentStartIdx < 0) {
                foreach ($rows as $i => $row) {
                    if (($row['type'] ?? '') === 'hour' && $row['start'] < $slotEnd && $row['end'] > $slotStart) {
                        $fragmentStartIdx = $i;
                        break;
                    }
                }
            }

            if ($fragmentStartIdx < 0) {
                continue;
            }

            $blockedByBreak = false;
            for ($i = $fragmentStartIdx; $i < $idx; $i++) {
                if (($rows[$i]['type'] ?? '') === 'break') {
                    $blockedByBreak = true;
                    $fragmentStartIdx = $i + 1;
                }
            }

            if ($blockedByBreak) {
                if (
                    $fragmentStartIdx < $idx
                    && ($rows[$fragmentStartIdx]['type'] ?? '') === 'hour'
                    && $rows[$fragmentStartIdx]['start'] < $rowStart
                ) {
                    return true;
                }
                continue;
            }

            return true;
        }

        return false;
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
