<?php

namespace App\Livewire\Tenants\Classes;

use App\Events\DataUpdatedEvent;
use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Gestion : PP et Responsables de classe')]
class ManageClasseYearlyResponsiblesComponent extends Component
{
    use WireUiActions, WithPagination;

    public Classe $classe;
    public ?string $classe_slug = null;

    // ─── Sélections ───────────────────────────────────────────────────
    public ?int $principalId = null;
    public ?int $respo1Id    = null;
    public ?int $respo2Id    = null;

    // ─── Recherche ────────────────────────────────────────────────────
    public string $studentSearch  = '';
    public string $teacherSearch  = '';

    public function mount(string $classe_slug)
    {
        if(!$classe_slug) return abort(404);

        $this->classe_slug  = $classe_slug;

        $classe = Classe::whereSlug($classe_slug)?->first();

        if(!$classe) return abort(404);

        $this->classe       = $classe;

        $this->principalId = $classe->principal_id;

        $this->respo1Id    = $classe->respo_1_id;

        $this->respo2Id    = $classe->respo_2_id;

    }

    public function updatedStudentSearch(): void { $this->resetPage(); }

    // ─── Computed ─────────────────────────────────────────────────────

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function teachers()
    {
        return ClasseSubjectOfSchoolYear::where('classe_id', $this->classe->id)
            ->where('school_year_id', $this->activeYear?->id)
            ->whereNull('ended_at')
            ->whereNotNull('teacher_id')
            ->with('teacher')
            ->get()
            ->pluck('teacher')
            ->filter()
            ->unique('id')
            ->when($this->teacherSearch, fn($col) =>
                $col->filter(fn($t) =>
                    str_contains(strtolower($t->name), strtolower($this->teacherSearch)) ||
                    str_contains(strtolower($t->email ?? ''), strtolower($this->teacherSearch))
                )
            )
            ->values();
    }

    #[Computed]
    public function students()
    {
        return Student::whereHas('yearlyClasseStudents', fn($q) =>
            $q->where('classe_id', $this->classe->id)
              ->where('school_year_id', $this->activeYear?->id)
              ->where('is_active', true)
        )
        ->when($this->studentSearch, fn($q) =>
            $q->where(fn($q) =>
                $q->where('name', 'like', '%'.$this->studentSearch.'%')
                  ->orWhere('prenames', 'like', '%'.$this->studentSearch.'%')
                  ->orWhere('matricule', 'like', '%'.$this->studentSearch.'%')
            )
        )
        ->orderBy('name')
        ->paginate(15);
    }

    // ─── Toggle principal ─────────────────────────────────────────────

    public function togglePrincipal(int $teacherId): void
    {
        // Décoche si déjà sélectionné
        $this->principalId = $this->principalId === $teacherId ? null : $teacherId;
    }

    // ─── Toggle respo ─────────────────────────────────────────────────

    public function toggleRespo1(int $studentId): void
    {
        // Ne peut pas être respo1 et respo2 en même temps
        if ($this->respo2Id === $studentId) {
            $this->respo2Id = null;
        }
        $this->respo1Id = $this->respo1Id === $studentId ? null : $studentId;
    }

    public function toggleRespo2(int $studentId): void
    {
        if ($this->respo1Id === $studentId) {
            $this->respo1Id = null;
        }
        $this->respo2Id = $this->respo2Id === $studentId ? null : $studentId;
    }

    // ─── Sauvegarde ───────────────────────────────────────────────────

    public function save(): void
    {
        // Respo1 et respo2 doivent être différents
        if ($this->respo1Id && $this->respo1Id === $this->respo2Id) {
            $this->notification()->error(
                title: 'Erreur',
                description: 'Les deux responsables doivent être des apprenants différents.',
            );
            return;
        }

        $this->classe->update([
            'principal_id' => $this->principalId,
            'respo_1_id'   => $this->respo1Id,
            'respo_2_id'   => $this->respo2Id,
        ]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
            title: 'Responsables mis à jour',
            description: 'Le professeur principal et les responsables ont été enregistrés.',
        );

        $this->redirect(
            route('tenant.classe.profil', ['classe_slug' => $this->classe->slug]),
            navigate: true
        );
    }

    public function render()
    {
        return view('livewire.tenants.classes.manage-classe-yearly-responsibles-component');
    }


    
}
