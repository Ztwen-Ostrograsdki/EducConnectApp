<?php

namespace App\Livewire\Tenants\Teachers;

use App\Events\DataUpdatedEvent;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherYearlySubject;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Gestion des matière de prof")]
class ManageTeacherSubjectsComponent extends Component
{
    use WireUiActions, WithPagination;

    // ─── Sélection prof ───────────────────────────────────────────────
    public ?Teacher    $teacher     = null;
    public ?int    $teacherId     = null;
    public string  $teacherSearch = '';
    public bool    $showDropdown  = false;

    // ─── Filtres matières disponibles ─────────────────────────────────
    public string $subjectSearch = '';
    public string $subjectLevel  = '';
    public string $subjectType   = '';

    public function mount(?string $teacher_uuid = null): void
    {
        if ($teacher_uuid) {
            $teacher = Teacher::where('uuid', $teacher_uuid)->first();
            if ($teacher) {
                $this->teacher     = $teacher;
                $this->teacherId     = $teacher->id;
                $this->teacherSearch = $teacher->user?->name ?? $teacher->email;
                $this->showDropdown  = false;
            }
            else{
                abort(404);
            }
        }
    }

    public function updatedTeacherSearch(): void
    {
        $this->showDropdown = strlen($this->teacherSearch) >= 2;
    }

    public function updatedSubjectSearch(): void { $this->resetPage(); }
    public function updatedSubjectLevel(): void  { $this->resetPage(); }
    public function updatedSubjectType(): void   { $this->resetPage(); }

    // ─── Sélection prof ───────────────────────────────────────────────

    public function selectTeacher(int $id, string $name): void
    {
        $this->teacherId     = $id;
        $this->teacherSearch = $name;
        $this->showDropdown  = false;
        $this->resetPage();
    }

    public function clearTeacher(): void
    {
        $this->teacherId     = null;
        $this->teacherSearch = '';
        $this->showDropdown  = false;
        $this->resetPage();
    }

    // ─── Computed ─────────────────────────────────────────────────────

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function teacherResults()
    {
        if (strlen($this->teacherSearch) < 2) {
            return collect();
        }

        return Teacher::where('status', 'active')
            ->whereHas('user', fn($q) =>
                $q->where('name', 'like', '%'.$this->teacherSearch.'%')
            )
            ->whereHas('yearlyAccesses', fn($qq) =>
                $qq->where('school_year_id', $this->activeYear->id)
                ->where('status', 'active')
            )
            ->orWhere('email', 'like', '%'.$this->teacherSearch.'%')
            ->orWhere('identifiant', 'like', '%'.$this->teacherSearch.'%')
            ->with('user')
            ->limit(8)
            ->get();
    }

    #[Computed]
    public function selectedTeacher(): ?Teacher
    {
        return $this->teacherId ? Teacher::with('user')->find($this->teacherId) : null;
    }

    #[Computed]
    public function linkedSubjects()
    {
        if (!$this->teacherId || !$this->activeYear) {
            return collect();
        }

        return TeacherYearlySubject::where('teacher_id', $this->teacherId)
            ->where('school_year_id', $this->activeYear->id)
            ->where('is_active', true)
            ->with('subject')
            ->get();
    }

    #[Computed]
    public function availableSubjects()
    {
        $linkedIds = $this->linkedSubjects->pluck('subject_id')->toArray();

        return Subject::where('is_active', true)
            ->when($this->subjectSearch, fn($q) =>
                $q->where(fn($q) =>
                    $q->where('name', 'like', '%'.$this->subjectSearch.'%')
                      ->orWhere('code', 'like', '%'.$this->subjectSearch.'%')
                )
            )
            ->when($this->subjectLevel, fn($q) => $q->where('level', $this->subjectLevel))
            ->when($this->subjectType,  fn($q) => $q->where('type', $this->subjectType))
            ->orderBy('name')
            ->paginate(12);
    }

    public function isLinked(int $subjectId): bool
    {
        return $this->linkedSubjects->contains('subject_id', $subjectId);
    }

    // ─── Actions ──────────────────────────────────────────────────────

    public function linkSubject(int $subjectId): void
    {
        if (!$this->teacherId || !$this->activeYear) return;

        TeacherYearlySubject::firstOrCreate([
            'teacher_id'     => $this->teacherId,
            'subject_id'     => $subjectId,
            'school_year_id' => $this->activeYear->id,
        ], [
            'is_active' => true,
        ]);

        TeacherYearlySubject::where('teacher_id', $this->teacherId)
            ->where('subject_id', $subjectId)
            ->where('school_year_id', $this->activeYear->id)
            ->update(['is_active' => true]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $subject = Subject::find($subjectId);
        $this->notification()->success(
            title: 'Matière liée',
            description: "« {$subject->name} » a été liée au prof avec succès.",
        );

        unset($this->linkedSubjects);
    }

    public function unlinkSubject(int $subjectId): void
    {
        if (!$this->teacherId || !$this->activeYear) return;

        $subject = Subject::find($subjectId);

        if(!$this->teacher->ensureThatTeacherDoesntHaveClasseWithThisSubject($subjectId)){

            $this->notification()->send([
                'icon'        => 'warning',
                'timeout' => 0,
                'title'       => "Vous ne pouvez pas retirer cette matière!",
                'description' => $this->teacher->getFullName() . " enseigne cette matière « {$subject->name} » dans au moins une classe. Pour lui retirer cette matière, vous devez d'abord lui retirer cette ou ces classe(s) concernée(s)!",
            ]);


            return;

        }

        TeacherYearlySubject::where('teacher_id', $this->teacherId)
            ->where('subject_id', $subjectId)
            ->where('school_year_id', $this->activeYear->id)
            ->delete();

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->warning(
            title: 'Matière retirée',
            description: "« {$subject->name} » a été retirée du prof.",
        );

        unset($this->linkedSubjects);
    }

    // ─── Render ───────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.tenants.teachers.manage-teacher-subjects-component');
    }
}
