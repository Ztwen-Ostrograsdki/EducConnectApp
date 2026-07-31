<?php

namespace App\Livewire\Tenants\Students;

use App\Livewire\Tenants\ActionsTraits\StudentsActions;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Services\MarksServices\ClasseSubjectMarksCacheService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Notes de classe pour apprenant")]
class StudentMarksComponent extends Component
{
    use WireUiActions, StudentsActions;
    
    protected const INTERRO_TYPES = ['interro1', 'interro2', 'interro3', 'interro4'];

    public $counter = 0;

    public ?int $period;

    public ?string $student_name;

    public ?string $student_uuid;

    public ?string $classe_slug;

    public ?string $period_type_selected;

    public ?string $school_year_selected;

    public function mount(string $student_uuid)
    {
        $this->student_uuid = $student_uuid;

        $this->classe_slug = $student_uuid;

        $this->loadActivePeriod();
    }

    
    public function loadActivePeriod()
    {
        if ($this->activeYear && $this->activeYear->is_active && $this->activeYear->active_period) {

            $this->period = $this->activeYear->active_period;
        }
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->resetMarksRelatedComputeds();

        $this->counter++;
    }

    public function updatedPeriod()
    {
        $this->resetMarksRelatedComputeds();
    }

    protected function resetMarksRelatedComputeds(): void
    {
        unset($this->subjectRows);
    }

    #[Computed]
    public function student()
    {
        $student = Student::firstWhere('uuid', $this->student_uuid);

        if (!$student) return abort(404);

        return $student;
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

    /**
     * Classe active de l'apprenant pour l'année en cours.
     * Adapte le nom de la relation si besoin ('yearlyClasseStudents' supposé BelongsTo/HasMany
     * vers YearlyClasseStudent, lui-même lié à Classe via classe()).
     */
    #[Computed]
    public function classe()
    {
        $classe_rel = $this->student->currentClasse();

        if($classe_rel) return $classe_rel->classe;

        return null;

    }

    /**
     * Toutes les matières de la classe pour l'année active, avec leur coefficient.
     */
    #[Computed]
    public function classeSubjects()
    {
        return ClasseSubjectOfSchoolYear::with('subject')
            ->where('classe_id', $this->classe->id)
            ->where('school_year_id', $this->activeYear->id)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->whereHas('subject', fn ($q) => $q->where('is_active', true))
            ->get()
            ->sortBy(fn ($cs) => $cs->subject->name)
            ->values();
    }

    /**
     * Tous les apprenants de la classe (pour calculer le rang de manière cohérente
     * avec la vue enseignant : même population de référence pour le classement).
     */
    #[Computed]
    public function classmates()
    {
        return Student::whereHas('yearlyClasseStudents', fn ($q) =>
            $q->where('classe_id', $this->classe->id)
              ->where('school_year_id', $this->activeYear->id)
              ->where('is_active', true)
        )->get(['id']);
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
     * Une ligne par matière : lecture cache (une clé par matière), calcul de
     * moy. interro / moy. / moy. coef. pour CET apprenant, et son rang dans
     * la classe pour cette matière/période.
     */
    #[Computed]
    public function subjectRows(): array
    {
        if (!$this->period) {
            return [];
        }

        $devoirColumns = array_keys($this->devoirColumns());
        $classmateIds = $this->classmates->pluck('id');

        return $this->classeSubjects->map(function (ClasseSubjectOfSchoolYear $classeSubject) use ($devoirColumns, $classmateIds) {

            $subject = $classeSubject->subject;

            $coefficient = $this->classe->getCoefValueOfSubject($subject->id);

            // Une seule lecture de cache par matière — clé indépendante,
            // aucun impact sur les autres matières/périodes déjà en cache.
            $marksData = app(ClasseSubjectMarksCacheService::class)->get(
                $this->classe->id,
                $subject->id,
                $this->period,
                $this->activeYear->id
            );

            // Moyennes de TOUS les camarades pour cette matière, afin de classer
            // correctement l'apprenant consulté (même logique que la vue enseignant).
            $moyennes = $classmateIds->mapWithKeys(function ($studentId) use ($marksData, $devoirColumns) {
                return [$studentId => $this->computeMoy($marksData[$studentId] ?? [], $devoirColumns)];
            });

            $ranked = $moyennes
                ->filter(fn ($moy) => !is_null($moy))
                ->sortDesc();

            $rank = null;
            $position = 0;
            $lastMoy = null;

            foreach ($ranked as $studentId => $moy) {
                $position++;
                if ($moy !== $lastMoy) {
                    $rank = $position;
                    $lastMoy = $moy;
                }
                if ($studentId === $this->student->id) {
                    break;
                }
            }

            $thisStudentMoy = $moyennes[$this->student->id] ?? null;

            if (is_null($thisStudentMoy)) {
                $rank = null;
            }

            $studentMarks = $marksData[$this->student->id] ?? [];

            $values = [];
            foreach (array_keys($this->markColumns) as $type) {
                $values[$type] = $studentMarks[$type]['value'] ?? null;
            }

            $moyInterro = $this->computeMoyInterro($studentMarks);
            $moyDevoirs = $this->computeMoyDevoirs($studentMarks, $devoirColumns);
            $moy = $thisStudentMoy;
            $moyCoef = !is_null($moy) ? round($moy * $coefficient, 2) : null;

            return [
                'subject'     => $subject,
                'coefficient' => $coefficient,
                'marks'       => $values,
                'moy_interro' => $moyInterro,
                'moy'         => $moy,
                'moy_coef'    => $moyCoef,
                'rank'        => $rank,
                'total'       => $classmateIds->count(),
            ];
        })->all();
    }

    #[Computed]
    public function getTotals()
    {
        $coef_sum = 0;

        $moy_coef_sum = 0;

        $moy = 0;

        foreach($this->subjectRows as $row){

            $coef = $row['coefficient'] && $row['moy_coef'] ? $row['coefficient'] : 0;

            $moy_coef = $row['moy_coef'] ?? 0;

            $coef_sum += $coef;

            $moy_coef_sum += $moy_coef;

        }

        if($moy_coef_sum && $coef_sum){

            $moy = round($moy_coef_sum/$coef_sum, 2);
        }



        return ['coef_sum' => $coef_sum, 'moy_coef_sum' => $moy_coef_sum, 'moy' => $moy];
    }

    protected function computeMoyInterro(array $studentMarks): ?float
    {
        $values = collect(self::INTERRO_TYPES)
            ->map(fn ($type) => $studentMarks[$type]['value'] ?? null)
            ->filter(fn ($v) => !is_null($v));

        return $values->isNotEmpty() ? round($values->avg(), 2) : null;
    }

    protected function computeMoyDevoirs(array $studentMarks, array $devoirColumns): ?float
    {
        $values = collect($devoirColumns)
            ->map(fn ($type) => $studentMarks[$type]['value'] ?? null)
            ->filter(fn ($v) => !is_null($v));

        return $values->isNotEmpty() ? round($values->avg(), 2) : null;
    }

    protected function computeMoy(array $studentMarks, array $devoirColumns): ?float
    {
        $moyInterro = $this->computeMoyInterro($studentMarks);
        $moyDevoirs = $this->computeMoyDevoirs($studentMarks, $devoirColumns);

        if (!is_null($moyInterro) && !is_null($moyDevoirs)) {
            return round(($moyInterro + $moyDevoirs) / 2, 2);
        }

        return $moyInterro ?? $moyDevoirs ?? null;
    }

    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->school_year_selected = $schoolYear;
    }

    #[On("StudentDataUpdatedEventLiveEvent")]
    public function studentDataUpdated()
    {
        $this->counter++;
    }

    public function render()
    {
        return view('livewire.tenants.students.student-marks-component');
    }

}
