<?php

namespace App\Livewire\Tenants\Classes;

use App\Events\DataUpdatedEvent;
use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Gestion des enseignants par matière de classe")]
class ManageYearlyClasseSubjectsTeacherComponent extends Component
{
    use WireUiActions, WithPagination;

    public Classe $classe;
    public ?string $classe_slug = null;

    // ─── Sélection en cours ───────────────────────────────────────────
    public ?int    $selectedSubjectId = null;
    public ?int    $selectedTeacherId = null;

    // ─── Recherche ────────────────────────────────────────────────────
    public string  $subjectSearch     = '';
    public string  $teacherSearch     = '';

    public function mount(string $classe_slug)
    {
        if(!$classe_slug) return abort(404);

        $classe = Classe::whereSlug($classe_slug)?->first();

        $this->classe_slug  = $classe_slug;

        if(!$classe) return abort(404);

        $this->classe = $classe;
    }

    public function updatedSubjectSearch(): void  
    { 
        $this->resetPage(); 
    }
    
    
    public function updatedSelectedSubjectId(): void
    {
        $this->selectedTeacherId = null;
        $this->teacherSearch     = '';
        unset($this->availableTeachers);
        unset($this->assignedTeacherForSubject);
        unset($this->selectedSubject);
    }

    // ─── Computed ─────────────────────────────────────────────────────

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function subjects()
    {
        return Subject::where('is_active', true)
            ->where('level', $this->classe->level)
            ->when($this->subjectSearch, fn($q) =>
                $q->where(fn($q) =>
                    $q->where('name', 'like', '%'.$this->subjectSearch.'%')
                      ->orWhere('code', 'like', '%'.$this->subjectSearch.'%')
                )
            )
            ->orderBy('name')
            ->paginate(10);
    }

    #[Computed]
    public function selectedSubject(): ?Subject
    {
        return $this->selectedSubjectId
            ? Subject::find($this->selectedSubjectId)
            : null;
    }

    #[Computed]
    public function assignedTeacherForSubject(): ?ClasseSubjectOfSchoolYear
    {
        if (!$this->selectedSubjectId || !$this->activeYear) return null;

        return ClasseSubjectOfSchoolYear::where('classe_id', $this->classe->id)
            ->where('subject_id', $this->selectedSubjectId)
            ->where('school_year_id', $this->activeYear->id)
            ->whereNull('ended_at')
            ->with(['teacher.user'])
            ->first();
    }

    #[Computed]
    public function availableTeachers()
    {
        if (!$this->selectedSubjectId || !$this->activeYear) return collect();

        return Teacher::where('status', 'active')
            // Accès valide pour l'année active
            ->whereHas('yearlyAccesses', fn($q) =>
                $q->where('school_year_id', $this->activeYear->id)
                ->where('status', 'active')
            )
            // Lié à la matière sélectionnée cette année
            ->whereHas('yearlySubjects', fn($q) =>
                $q->where('subject_id', $this->selectedSubjectId)
                ->where('school_year_id', $this->activeYear->id)
                ->where('is_active', true)
            )
            ->when($this->teacherSearch, fn($q) =>
                $q->where(fn($q) =>
                    $q->whereHas('user', fn($q) =>
                        $q->where('name', 'like', '%'.$this->teacherSearch.'%')
                        ->orWhere('prenames', 'like', '%'.$this->teacherSearch.'%')
                        ->orWhere('email', 'like', '%'.$this->teacherSearch.'%')
                    )
                    
                    ->orWhere('identifiant', 'like', '%'.$this->teacherSearch.'%')
                )
            )
            ->with('user')
            ->orderBy('email')
            ->get();
    }

    #[Computed]
    public function assignedLinks()
    {
        if (!$this->activeYear) return collect();

        return ClasseSubjectOfSchoolYear::where('classe_id', $this->classe->id)
            ->where('school_year_id', $this->activeYear->id)
            ->whereNull('ended_at')
            ->with(['subject', 'teacher.user'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // ─── Sélection matière ────────────────────────────────────────────

    public function selectSubject(int $subjectId): void
    {
        if ($this->selectedSubjectId === $subjectId) {
            $this->selectedSubjectId = null;
            $this->selectedTeacherId = null;
            unset($this->availableTeachers);
            unset($this->assignedTeacherForSubject);
            return;
        }

        $this->selectedSubjectId = $subjectId;
        $this->selectedTeacherId = null;
        $this->teacherSearch     = '';

        // Invalide le cache pour forcer le rechargement avec la nouvelle matière
        unset($this->availableTeachers);
        unset($this->assignedTeacherForSubject);
        unset($this->selectedSubject);
    }

    // ─── Assigner prof à matière ──────────────────────────────────────

    public function assignTeacher(int $teacherId): void
    {
        if (!$this->selectedSubjectId || !$this->activeYear) return;

        // ── Vérifier que le prof enseigne bien cette matière ──────────
        $teachesSubject = \App\Models\TeacherYearlySubject::where('teacher_id', $teacherId)
            ->where('subject_id', $this->selectedSubjectId)
            ->where('school_year_id', $this->activeYear->id)
            ->where('is_active', true)
            ->exists();

        if (!$teachesSubject) {
            $this->notification()->error(
                title: 'Matière non enseignée',
                description: 'Ce professeur n\'enseigne pas cette matière. Vérifiez ses matières assignées.',
            );
            return;
        }

        // ── Vérifier si déjà pris dans cette classe ────────────────────
        $existing = ClasseSubjectOfSchoolYear::where('classe_id', $this->classe->id)
            ->where('subject_id', $this->selectedSubjectId)
            ->where('school_year_id', $this->activeYear->id)
            ->whereNull('ended_at')
            ->first();

        if ($existing) {
            $this->notification()->error(
                title: 'Matière déjà assignée',
                description: 'Cette matière est déjà prise dans cette classe. Retirez d\'abord le prof actuel.',
            );
            return;
        }

        ClasseSubjectOfSchoolYear::create([
            'classe_id'      => $this->classe->id,
            'subject_id'     => $this->selectedSubjectId,
            'teacher_id'     => $teacherId,
            'school_year_id' => $this->activeYear->id,
            'is_active'      => true,
            'started_at'     => now(),
        ]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $teacher = Teacher::with('user')->find($teacherId);
        $subject = Subject::find($this->selectedSubjectId);

        $this->notification()->success(
            title: 'Prof assigné',
            description: "{$teacher?->user?->name} assigné à « {$subject?->name} ».",
        );

        $this->selectedSubjectId = null;
        $this->selectedTeacherId = null;

        unset($this->assignedLinks);
        unset($this->assignedTeacherForSubject);
    }

    // ─── Retirer un lien ──────────────────────────────────────────────

    public function removeLink(int $linkId): void
    {
        $link = ClasseSubjectOfSchoolYear::with(['subject', 'teacher.user'])->findOrFail($linkId);

        $link->update([
            'ended_at'           => now(),
            'replacement_reason' => 'Remaniement d\'emploi du temps',
            'replaced_by'        => auth('tenant')->id(),
        ]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->warning(
            title: 'Lien retiré',
            description: "{$link->teacher?->user?->name} retiré de « {$link->subject?->name} ».",
        );

        unset($this->assignedLinks);
        unset($this->assignedTeacherForSubject);
    }

    // ─── Render ───────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.tenants.classes.manage-yearly-classe-subjects-teacher-component');
    }
}
