<?php

namespace App\Livewire\Tenants\Classes\Sections;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Services\MarksServices\ClasseSubjectMarksCacheService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ClasseMarksPage extends Component
{
    use WireUiActions;

    protected const INTERRO_TYPES = ['interro1', 'interro2', 'interro3', 'interro4'];

    // Devient l'état "courant" sélectionnable, plutôt qu'une donnée figée de route.
    public ?string $subject_slug = null;

    public ?int $classe_subject_id = null;

    public ?int $period = null;

    public ?string $classroom = null;

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
        unset($this->marksData);

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

    /**
     * Le semestre/trimestre change → nouvelle clé de cache, on force le recalcul
     * du computed Livewire pour relire (le cache Laravel, lui, gère déjà l'ancienne
     * clé indépendamment sans jamais la recalculer inutilement).
     */
    public function updatedPeriod()
    {
        session()->put('classe_marks_period_selected', $this->period);
        
        $this->resetMarksRelatedComputeds();
    }

    /**
     * La matière change → même logique, mais on doit aussi recharger classe_subject
     * (le coefficient et l'assignation dépendent de la matière sélectionnée) et
     * la liste des colonnes de devoirs si jamais elle dépendait de la matière
     * (pas le cas ici, elle dépend du tenant, mais on reset par prudence/cohérence).
     */
    public function updatedSubjectSlug(?string $subject_slug)
    {
        unset($this->classe_subject, $this->subject);

        if($subject_slug){

            $this->classe_subject_id = $this->classe_subject->id;
        }

        session()->put('classe_marks_subject_selected', $this->subject_slug);

        $this->resetMarksRelatedComputeds();
    }

    protected function resetMarksRelatedComputeds(): void
    {
        unset($this->marksData, $this->studentsRows);
    }

    /**
     * Placeholder pour le moment : ouvrira un modal d'édition des notes de l'apprenant.
     */
    public function editStudentMark(int $student_id)
    {
        // À implémenter : ouverture du formulaire/modal d'édition des notes.
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
    public function studentsRows(): array
    {
        $devoirColumns = array_keys($this->devoirColumns());
        $coefficient = (float) ($this->classe_subject->coefficient ?? 0);

        $rows = $this->students->map(function (Student $student) use ($devoirColumns, $coefficient) {

            $studentMarks = $this->marksData[$student->id] ?? [];

            $values = [];

            foreach (array_keys($this->markColumns) as $type) {
                $values[$type] = $studentMarks[$type]['value'] ?? null;
            }

            $interroValues = array_filter(
                array_intersect_key($values, array_flip(self::INTERRO_TYPES)),
                fn ($v) => !is_null($v)
            );

            $moyInterro = !empty($interroValues)
                ? round(array_sum($interroValues) / count($interroValues), 2)
                : null;

            $devoirValues = array_filter(
                array_intersect_key($values, array_flip($devoirColumns)),
                fn ($v) => !is_null($v)
            );

            $moyDevoirs = !empty($devoirValues)
                ? round(array_sum($devoirValues) / count($devoirValues), 2)
                : null;

            if (!is_null($moyInterro) && !is_null($moyDevoirs)) {
                $moy = round(($moyInterro + $moyDevoirs) / 2, 2);
            } elseif (!is_null($moyInterro)) {
                $moy = $moyInterro;
            } elseif (!is_null($moyDevoirs)) {
                $moy = $moyDevoirs;
            } else {
                $moy = null;
            }

            $moyCoef = !is_null($moy) ? round($moy * $coefficient, 2) : null;

            return [
                'student'     => $student,
                'marks'       => $values,
                'moy_interro' => $moyInterro,
                'moy'         => $moy,
                'moy_coef'    => $moyCoef,
            ];
        });

        $ranked = $rows->sortByDesc(fn ($row) => $row['moy'] ?? -1)->values();

        $rank = 0;
        $lastMoy = null;
        $position = 0;

        $ranked = $ranked->map(function ($row) use (&$rank, &$lastMoy, &$position) {

            $position++;

            if (is_null($row['moy'])) {
                $row['rank'] = null;
                return $row;
            }

            if ($row['moy'] !== $lastMoy) {
                $rank = $position;
                $lastMoy = $row['moy'];
            }

            $row['rank'] = $rank;

            return $row;
        });

        return $ranked->sortBy(fn ($row) => $row['student']->name . $row['student']->prenames)->values()->all();
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
        $classe_subject = ClasseSubjectOfSchoolYear::with(['classe', 'subject'])
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
        return view('livewire.tenants.classes.sections.classe-marks-page');
    }
}
