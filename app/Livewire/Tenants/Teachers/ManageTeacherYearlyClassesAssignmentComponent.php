<?php

namespace App\Livewire\Tenants\Teachers;

use App\Events\DataUpdatedEvent;
use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
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
#[Title("Gestion des classes par enseignant")]
class ManageTeacherYearlyClassesAssignmentComponent extends Component
{
    use WireUiActions, WithPagination;

    public Teacher $teacher;

    // ─── Sélection en cours ───────────────────────────────────────────
    public ?int $selectedClasseId = null;

    // ─── Recherche ────────────────────────────────────────────────────
    public string $classeSearch  = '';
    public string $subjectSearch = '';

    // ─── État de chargement ───────────────────────────────────────────
    public bool $assigningSubjectId = false;

    public function mount(string $teacher_uuid)
    {
        if (!$teacher_uuid) return abort(404);

        $teacher = Teacher::where('uuid', $teacher_uuid)
            ->with('user')
            ->first();

        if (!$teacher) return abort(404);

        $this->teacher = $teacher;
    }

    public function updatedClasseSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedClasseId(): void
    {
        $this->subjectSearch = '';
        unset($this->subjectsForSelectedClasse);
        unset($this->teacherYearlySubjects);
    }

    // ─── Computed ─────────────────────────────────────────────────────

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function classes()
    {
        return Classe::query()
            ->when($this->classeSearch, fn($q) =>
                $q->where(fn($q) =>
                    $q->where('name', 'like', '%' . $this->classeSearch . '%')
                      ->orWhere('slug', 'like', '%' . $this->classeSearch . '%')
                )
            )
            ->orderBy('name')
            ->paginate(10);
    }

    #[Computed]
    public function selectedClasse(): ?Classe
    {
        return $this->selectedClasseId
            ? Classe::find($this->selectedClasseId)
            : null;
    }

    /**
     * Les matières que l'enseignant enseigne cette année.
     */
    #[Computed]
    public function teacherYearlySubjects()
    {
        if (!$this->activeYear) return collect();

        return TeacherYearlySubject::where('teacher_id', $this->teacher->id)
            ->where('school_year_id', $this->activeYear->id)
            ->where('is_active', true)
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->filter();
    }

    #[Computed]
    public function subjectsForSelectedClasse()
    {
        if (!$this->selectedClasseId || !$this->activeYear) return collect();

        $activeLinks = ClasseSubjectOfSchoolYear::where('classe_id', $this->selectedClasseId)
            ->where('school_year_id', $this->activeYear->id)
            ->whereNull('ended_at')
            ->with('teacher.user')
            ->get()
            ->keyBy('subject_id');

        return $this->teacherYearlySubjects
            ->when($this->subjectSearch, fn($c) =>
                $c->filter(fn($s) =>
                    str_contains(strtolower($s->name), strtolower($this->subjectSearch)) ||
                    str_contains(strtolower($s->code ?? ''), strtolower($this->subjectSearch))
                )
            )
            ->map(function (Subject $subject) use ($activeLinks) {
                $link = $activeLinks->get($subject->id);

                return [
                    'subject'      => $subject,
                    'link'         => $link,
                    'assigned_by_self' => $link && $link->teacher_id === $this->teacher->id,
                    'taken_by_other'   => $link && $link->teacher_id !== $this->teacher->id,
                ];
            });
    }

    /**
     * Récap : toutes les assignations actives de ce prof, toutes classes.
     */
    #[Computed]
    public function allAssignedLinks()
    {
        if (!$this->activeYear) return collect();

        return ClasseSubjectOfSchoolYear::where('teacher_id', $this->teacher->id)
            ->where('school_year_id', $this->activeYear->id)
            ->whereNull('ended_at')
            ->with(['classe', 'subject'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // ─── Actions ──────────────────────────────────────────────────────

    public function selectClasse(int $classeId): void
    {
        if ($this->selectedClasseId === $classeId) {
            $this->selectedClasseId = null;
            unset($this->subjectsForSelectedClasse);
            unset($this->selectedClasse);
            return;
        }

        $this->selectedClasseId = $classeId;
        $this->subjectSearch    = '';

        unset($this->subjectsForSelectedClasse);
        unset($this->selectedClasse);
    }

    public function toggleSubject(int $subjectId): void
    {
        if (!$this->selectedClasseId || !$this->activeYear) return;

        // Vérification : le prof enseigne bien cette matière
        $teachesSubject = TeacherYearlySubject::where('teacher_id', $this->teacher->id)
            ->where('subject_id', $subjectId)
            ->where('school_year_id', $this->activeYear->id)
            ->where('is_active', true)
            ->exists();

        if (!$teachesSubject) {
            $this->notification()->error(
                title: 'Matière non enseignée',
                description: 'Ce professeur n\'enseigne pas cette matière cette année.',
            );
            return;
        }

        $existing = ClasseSubjectOfSchoolYear::where('classe_id', $this->selectedClasseId)
            ->where('subject_id', $subjectId)
            ->where('school_year_id', $this->activeYear->id)
            ->whereNull('ended_at')
            ->first();

        // ── Décocher : retrait ────────────────────────────────────────
        if ($existing && $existing->teacher_id === $this->teacher->id) {
            if ($existing->started_at->gt(now()->subWeeks(2))) {
                $existing->update([
                    'ended_at'           => now(),
                    'replacement_reason' => 'Désassignation manuelle',
                    'replaced_by'        => auth('tenant')->id(),
                ]);
            } else {
                $existing->delete();
            }

            broadcast(new DataUpdatedEvent(tenant('id')));

            $subject = Subject::find($subjectId);
            $this->notification()->warning(
                title: 'Matière retirée',
                description: "« {$subject?->name} » retirée de la classe {$this->selectedClasse?->name}.",
            );

            unset($this->subjectsForSelectedClasse);
            unset($this->allAssignedLinks);
            return;
        }

        // ── Déjà prise par un autre prof ─────────────────────────────
        if ($existing && $existing->teacher_id !== $this->teacher->id) {
            $otherName = $existing->teacher?->user?->name ?? 'Un autre enseignant';
            $this->notification()->error(
                title: 'Matière déjà assignée',
                description: "{$otherName} enseigne déjà cette matière dans cette classe.",
            );
            return;
        }

        // ── Cocher : assignation ──────────────────────────────────────
        ClasseSubjectOfSchoolYear::create([
            'classe_id'      => $this->selectedClasseId,
            'subject_id'     => $subjectId,
            'teacher_id'     => $this->teacher->id,
            'school_year_id' => $this->activeYear->id,
            'is_active'      => true,
            'started_at'     => now(),
        ]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $subject = Subject::find($subjectId);
        $this->notification()->success(
            title: 'Matière assignée',
            description: "« {$subject?->name} » assignée dans la classe {$this->selectedClasse?->name}.",
        );

        unset($this->subjectsForSelectedClasse);
        unset($this->allAssignedLinks);
    }

    public function removeLink(int $linkId): void
    {
        $link = ClasseSubjectOfSchoolYear::with(['subject', 'classe'])
            ->where('teacher_id', $this->teacher->id)
            ->findOrFail($linkId);

        if ($link->started_at->gt(now()->subWeeks(2))) {
            $link->update([
                'ended_at'           => now(),
                'replacement_reason' => 'Retrait depuis récapitulatif',
                'replaced_by'        => auth('tenant')->id(),
            ]);
        } else {
            $link->delete();
        }

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->warning(
            title: 'Lien retiré',
            description: "« {$link->subject?->name} » retirée de la classe {$link->classe?->name}.",
        );

        unset($this->allAssignedLinks);
        unset($this->subjectsForSelectedClasse);
    }

    public function render()
    {
        return view('livewire.tenants.teachers.manage-teacher-yearly-classes-assignment-component');
    }
}