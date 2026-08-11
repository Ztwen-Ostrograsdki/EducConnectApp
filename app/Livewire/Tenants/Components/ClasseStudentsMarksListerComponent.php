<?php

namespace App\Livewire\Tenants\Components;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Services\MarksServices\ClasseSubjectMarksCacheService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ClasseStudentsMarksListerComponent extends Component
{
    protected const INTERRO_TYPES = ['interro1', 'interro2', 'interro3', 'interro4'];

    // Devient l'état "courant" sélectionnable, plutôt qu'une donnée figée de route.
    public ?string $subject_slug = null;

    public ?int $classe_subject_id = null;

    public ?int $period = null;

    public ?Classe $classe = null;

    public ?string $classe_slug = null;

    public $counter = 0;

    public function mount()
    {
        if(session()->has('classe_marks_period_selected')){

            $this->period = session('classe_marks_period_selected');

        }

        if(session()->has('classe_marks_subject_selected')){

            $this->subject_slug = session('classe_marks_subject_selected');

        }

        $this->loadActivePeriod();
    }

    public function loadActivePeriod()
    {
        if($this->activeYear && $this->activeYear->is_active && $this->activeYear->active_period){

            $this->period = $this->activeYear->active_period;
        }

    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        unset($this->marksData, $this->studentsRows, $this->coef_relation);

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


    public function updatedSubjectSlug(?string $subject_slug): void
    {
        // 1. Invalider TOUS les computeds liés à la matière
        $this->resetSubjectRelatedComputeds();

        // 2. Réinitialiser l'id
        $this->classe_subject_id = null;

        // 3. Accès SÉCURISÉ (ne plante plus si la matière n'a pas d'assignation)
        if ($subject_slug) {
            $classeSubject = $this->classe_subject; // recalculé avec le nouveau slug
            $this->classe_subject_id = $classeSubject?->id;
        }

        // 4. Persister le choix
        session()->put('classe_marks_subject_selected', $this->subject_slug);
    }

    /**
     * Invalide tous les computed qui dépendent de la matière / période.
     */
    protected function resetSubjectRelatedComputeds(): void
    {
        unset(
            $this->subject,
            $this->classe_subject,
            $this->marksData,
            $this->studentsRows,
            $this->coef_relation
        );
    }

    /**
     * Alias clair (tu avais déjà resetMarksRelatedComputeds).
     */
    protected function resetMarksRelatedComputeds(): void
    {
        $this->resetSubjectRelatedComputeds();
    }

    public function updatedPeriod(): void
    {
        session()->put('classe_marks_period_selected', $this->period);

        unset($this->marksData, $this->studentsRows);
    }


    #[Computed]
    public function students()
    {
        return Student::whereHas('yearlyClasseStudents', fn($q) =>
            $q->where('classe_id', $this->classe->id)
              ->where('school_year_id', $this->classe->school_year_id)
              ->where('is_active', true)
        )
        ->orderBy('name')
        ->orderBy('prenames')
        ->get();
    }

    #[Computed]
    public function subject()
    {
        if(!$this->subject_slug) return null;

        $subject = Subject::firstWhere('slug', $this->subject_slug);

        if(!$subject) return null;

        return $subject;
    }

    #[Computed]
    public function user()
    {
        return auth('tenant')->user();
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function periods_types()
    {
        return $this->activeYear->getPeriods();
    }

    #[Computed]
    public function devoirColumns(): array
    {
        $devoirsType = tenant()->devoirs_type ?? 'devoir1-devoir2';

        return $devoirsType === 'devoir1-compo'
            ? ['devoir1' => 'Devoir 1', 'compo' => 'Composition']
            : ['devoir1' => 'Devoir 1', 'devoir2' => 'Devoir 2'];
    }

    #[Computed]
    public function markColumns(): array
    {
        return [
            'interro1' => 'Interro 1',
            'interro2' => 'Interro 2',
            'interro3' => 'Interro 3',
            'interro4' => 'Interro 4',
        ] + $this->devoirColumns();
    }

    #[Computed]
    public function totalColumns(): int
    {
        return 5 + count($this->markColumns);
    }

    

    /**
     * Notes de la classe pour la matière/période COURANTES (peuvent avoir changé
     * via les selects), lues depuis le cache — clé différente à chaque combinaison,
     * gérée nativement par ClasseSubjectMarksCacheService, sans surcoût de notre part.
     */
    #[Computed]
    public function marksData(): array
    {
        
        if($this->classe && $this->subject && $this->period && $this->activeYear){

            return app(ClasseSubjectMarksCacheService::class)->get(
                $this->classe->id,
                $this->subject->id,
                $this->period,
                $this->activeYear->id
            );
        }

        return [];
    }

    #[Computed]
    public function coef_relation()
    {
        if(!$this->subject || !$this->subject_slug) return null;

        return $this->classe->getCoefOfSubject($this->subject->id);
    }


    #[Computed]
    public function studentsRows(): array
    {
        $marksData = $this->marksData; // classe_id, subject_id, period déjà résolus dans marksData()

        return $this->students->map(function (Student $student) use ($marksData) {

            $data = $marksData[$student->id] ?? [
                'marks' => [], 'moy_interro' => null, 'moy' => null,
                'moy_coef' => null, 'rank' => null, 'total' => 0,
            ];

            return [
                'student'     => $student,
                'marks'       => collect(array_keys($this->markColumns))
                                    ->mapWithKeys(fn ($t) => [$t => $data['marks'][$t]['value'] ?? null])->all(),
                'moy_interro' => $data['moy_interro'],
                'moy'         => $data['moy'],
                'moy_coef'    => $data['moy_coef'],
                'rank'        => $data['rank'],
            ];
        })
        ->sortBy(fn ($row) => $row['student']->name . $row['student']->prenames)
        ->values()
        ->all();
    }


    /**
     * Matières que ce prof enseigne dans CETTE classe, pour l'année active.
     * Alimente le select de switch de matière.
     */
    #[Computed]
    public function availableSubjects()
    {
        return Subject::query()
            ->whereHas('classeSubjects', fn ($q) =>
                $q->where('classe_id', $this->classe->id)
                  ->where('school_year_id', $this->activeYear->id)
                  ->where('is_active', true)
                  ->whereNull('ended_at')
            )
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function classe_subject()
    {
        if (!$this->classe || !$this->subject_slug || !$this->activeYear) {
            return null;
        }
        $classe_subject = ClasseSubjectOfSchoolYear::with(['classe', 'subject', 'teacher'])
                   ->whereHas('classe', fn($qc) =>
                        $qc->where('slug', $this->classe_slug)
                   )
                   ->whereHas('subject', fn($qs) =>
                        $qs->where('slug', $this->subject_slug)
                )->where('school_year_id', $this->activeYear->id)->whereNull('ended_at')->where('is_active', true)->first();

        if(!$classe_subject) return null;

        return $classe_subject;
    }

    
    public function render()
    {
        return view('livewire.tenants.components.classe-students-marks-lister-component');
    }
}
