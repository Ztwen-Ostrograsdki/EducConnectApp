<?php

namespace App\Livewire\Tenants\Users\Teacher;

use App\Livewire\Tenants\ActionsTraits\TimePlanActions;
use App\Models\Mark;
use App\Models\SchoolYear;
use App\Models\TimePlanSlot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;


class TeacherDashboard extends Component
{
    use WireUiActions, TimePlanActions;

    public $counter = 0;


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
    }
    
    #[On('TeacherWasBlockedLiveEvent')]
    public function teacherBlocked()
    {
        $this->notification()->send([
            'icon'        => 'warning',
            'title'       => "Votre compte enseignant a été bloqué",
            'timeout' => 0,
        ]);
        
        $this->counter++;

        return $this->redirect(route('tenant.my.profil'));
    }


    #[Computed]
    public function teacher()
    {
        return auth('tenant')->user()->teacher;
    } 
    
    
    #[Computed]
    public function user()
    {
        return auth('tenant')->user();
    }

    #[Computed]
    public function classes()
    {
        return $this->teacher?->getTeacherClassesWithSubjectsForThisSchoolYear();
    }
    
    
    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }
    
    
    #[Computed]
    public function teachersMarksCount(): ?array
    {
        $marks = [];

        foreach($this->classes as $rel){

            $counts = Mark::query()
                ->where('school_year_id', $this->activeYear->id)
                ->where('period', $this->activeYear->active_period)
                ->where('classe_id', $rel->classe_id)
                ->where('teacher_id', $this->teacher->id)
                ->select('subject_id', DB::raw('COUNT(*) as marks_count'))
                ->groupBy('subject_id')
                ->pluck('marks_count', 'subject_id')->toArray();

            $marks[$rel->classe_id] = $counts;
        }

        return $marks;
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


    public function downloadTimePlan()
    {
        
    }









    public function render()
    {
        return view('livewire.tenants.users.teacher.teacher-dashboard');
    }
}
