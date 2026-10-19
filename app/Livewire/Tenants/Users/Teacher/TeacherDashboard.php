<?php

namespace App\Livewire\Tenants\Users\Teacher;

use App\Jobs\JobToGeneratePrintableTimePlans;
use App\Livewire\Tenants\ActionsTraits\DocumentActionsTrait;
use App\Livewire\Tenants\ActionsTraits\TimePlanActions;
use App\Models\GeneratedDocument;
use App\Models\Mark;
use App\Models\SchoolYear;
use App\Services\TeachersServices\TeacherTimePlanService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class TeacherDashboard extends Component
{
    use WireUiActions, TimePlanActions, DocumentActionsTrait;

    public function boot(TeacherTimePlanService $teacherTimePlan): void
    {
        $this->teacherTimePlan = $teacherTimePlan;
    }

    protected TeacherTimePlanService $teacherTimePlan;

    public $counter = 0;

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
        unset($this->myTimePlanIsDownloadable);
        $this->refreshDocumentsList();
    }

    #[On('TeacherWasBlockedLiveEvent')]
    public function teacherBlocked()
    {
        $this->notification()->send([
            'icon'    => 'warning',
            'title'   => 'Votre compte enseignant a été bloqué',
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

        foreach ($this->classes as $rel) {
            $counts = Mark::query()
                ->where('school_year_id', $this->activeYear->id)
                ->where('period', $this->activeYear->active_period)
                ->where('classe_id', $rel->classe_id)
                ->where('teacher_id', $this->teacher->id)
                ->select('subject_id', DB::raw('COUNT(*) as marks_count'))
                ->groupBy('subject_id')
                ->pluck('marks_count', 'subject_id')
                ->toArray();

            $marks[$rel->classe_id] = $counts;
        }

        return $marks;
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

    /**
     * Génère le PDF de l’emploi du temps de l’enseignant connecté
     * (sans passer par le manager d’impression).
     */
    public function downloadTimePlan(): void
    {
        $teacher = $this->teacher;
        $year    = $this->activeYear;

        if (! $teacher) {
            $this->notification()->error(
                title: 'Profil enseignant introuvable',
                description: 'Impossible de générer l’emploi du temps.',
            );

            return;
        }

        if (! $year) {
            $this->notification()->error(
                title: 'Année scolaire absente',
                description: 'Aucune année scolaire active n’a été trouvée.',
            );

            return;
        }

        if ($this->teacherSlots->isEmpty()) {
            $this->notification()->info(
                title: 'Aucun créneau',
                description: 'Votre emploi du temps ne contient aucun créneau pour l’année en cours.',
            );

            return;
        }

        $fullName = method_exists($teacher, 'getFullName')
            ? $teacher->getFullName()
            : ($teacher->user?->getFullName() ?? 'enseignant');

        $docTitle = 'EDT-' . Str::slug($fullName) . '-' . ($year->slug ?? $year->id);

        try {
            if ($this->myTimePlanIsDownloadable) {
                // Suppression silencieuse de l’ancien PDF (fichier + DB) via le trait
                $this->deleteDocument($this->myTimePlanIsDownloadable);
            }

            JobToGeneratePrintableTimePlans::dispatch(
                tenantId: tenant('id'),
                notifiableId: (int) auth('tenant')->id(),
                mode: 'teachers',
                school_year_id: (int) $year->id,
                docTitle: $docTitle,
                config: [
                    'teacher_ids' => [(int) $teacher->id],
                ],
            );

            $this->notification()->success(
                title: 'Génération lancée',
                description: 'Votre emploi du temps est en cours de génération. Vous serez notifié dès qu’il sera prêt.',
            );
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'ERREUR',
                description: "Une erreur s'est produite lors de la génération de votre emploi du temps!",
            );
        }
    }

    #[Computed]
    public function myTimePlanIsDownloadable(): ?int
    {
        $doc = GeneratedDocument::ofType('time_plan')
            ->forUser(auth('tenant')->id())
            ->where('school_year_id', $this->activeYear?->id)
            ->where('for_teacher_id', $this->teacher?->id)
            ->first();

        return $doc?->id;
    }

    /**
     * DocumentActionsTrait attend un computed/property `documents`.
     * Ici on n’affiche pas de liste paginée : no-op pour refreshDocumentsList.
     */
    protected function refreshDocumentsList(): void
    {
        unset($this->myTimePlanIsDownloadable);
    }

    /**
     * Après suppression d’un doc EDT, invalider le computed dédié.
     * (deleteDocument du trait appelle déjà refreshDocumentsList)
     */
    public function render()
    {
        return view('livewire.tenants.users.teacher.teacher-dashboard');
    }
}
