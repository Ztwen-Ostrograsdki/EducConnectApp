<?php

namespace App\Livewire\Tenants\Students;

use App\Livewire\Tenants\ActionsTraits\StudentsActions;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Services\MarksServices\ClasseAveragesCacheService;
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
        if (!$this->period) return [];

        $marksService = app(ClasseSubjectMarksCacheService::class);

        $classeSubjects = ClasseSubjectOfSchoolYear::with('subject')
            ->where('classe_id', $this->classe->id)
            ->where('school_year_id', $this->activeYear->id)
            ->where('is_active', true)
            ->with(['teacher', 'subject'])
            ->whereNull('ended_at')
            ->get();


        return $classeSubjects->map(function (ClasseSubjectOfSchoolYear $classeSubject) use ($marksService) {

            $data = $marksService->forStudent(
                $this->classe->id,
                $classeSubject->subject_id,
                $this->student->id,
                $this->period,
                $this->activeYear->id
            ) ?? ['marks' => [], 'moy_interro' => null, 'moy' => null, 'moy_coef' => null, 'rank' => null, 'total' => 0];

            return [
                'subject'     => $classeSubject->subject,
                'teacher'     => $classeSubject->teacher,
                'coefficient' => $data['coefficient'],
                'marks'       => collect(array_keys($this->markColumns))
                                    ->mapWithKeys(fn ($t) => [$t => $data['marks'][$t]['value'] ?? null])->all(),
                'moy_interro' => $data['moy_interro'],
                'moy'         => $data['moy'],
                'moy_coef'    => $data['moy_coef'],
                'rank'        => $data['rank'],
                'total'       => $data['total'],
                'mention'     => $data['mention'],
            ];
        })->all();
    }

    /**
     * Moyenne générale + rang de l'apprenant pour la période — lecture directe
     * dans le cache ClasseAveragesCacheService (déjà calculé pour toute la classe).
     */
    #[Computed]
    public function termAverage(): ?array
    {
        if (!$this->period) return null;

        return app(ClasseAveragesCacheService::class)->forStudent(
            $this->classe->id,
            $this->student->id,
            $this->period,
            $this->activeYear->id
        );
        // => ['sum_moy_coef' => .., 'sum_coef' => .., 'moyenne' => .., 'mention' => .., 'rank' => .., 'total' => ..]
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
