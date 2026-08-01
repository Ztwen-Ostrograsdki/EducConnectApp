<?php

namespace App\Livewire\Tenants\Classes\Sections;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Services\ClassesServices\ClasseEffectifsService;
use App\Services\MarksServices\ClasseAveragesCacheService;
use App\Services\MarksServices\ClasseSubjectMarksCacheService;
use App\Services\MarksServices\SubjectAverageCalculator;
use App\Services\MentionService;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ClassePupilBulletinComponent extends Component
{
    public ?int $student_id = null;

    public ?int $period = null;

    public ?string $school_year_selected;

    public ?Classe $classe = null;

    public ?Student $student = null;

    public int $counter = 0;


    public function render()
    {
        return view('livewire.tenants.classes.sections.classe-pupil-bulletin-component');
    }


    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function devoirColumns(): array
    {
        $devoirsType = tenant()->devoirs_type ?? 'devoir1-devoir2';

        return $devoirsType === 'devoir1-compo'
            ? ['devoir1' => 'Dev 1', 'compo' => 'Compo']
            : ['devoir1' => 'Dev 1', 'devoir2' => 'Dev 2'];
    }

    #[On('ReloadForNewStudent')]
    public function reload($period, $student_id, $classe_id)
    {
        $this->counter++;

        $this->period = $period;

        if($student_id) $this->student = Student::find($student_id);

        if($classe_id) $this->classe = Classe::find($classe_id);

        unset($this->termAverage, $this->subjectsDetail);

    }


    #[Computed]
    public function effectifs()
    {
        $effectifs = app(ClasseEffectifsService::class)->getEffectifs($this->classe->id);

        return $effectifs;
    }

    #[Computed]
    public function subjectsDetail(): array
    {
        if (!$this->period) return [];

        $devoirColumns = SubjectAverageCalculator::devoirColumns();

        $marksService = app(ClasseSubjectMarksCacheService::class);

        $classeSubjects = ClasseSubjectOfSchoolYear::with('subject')
            ->where('classe_id', $this->classe->id)
            ->where('school_year_id', $this->activeYear->id)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->get();

        return $classeSubjects->map(function (ClasseSubjectOfSchoolYear $cs) use ($marksService, $devoirColumns) {

            // Lecture cache : classe_marks:{classeId}:{subjectId}:sy:{id}:period:{period}
            $marksData = $marksService->get(
                $this->classe->id,
                $cs->subject_id,
                $this->period,
                $this->activeYear->id
            );

            $studentMarks = $marksData[$this->student->id] ?? [];

            $moy = SubjectAverageCalculator::moy($studentMarks, $devoirColumns);

            $coefficient = $this->classe->getCoefValueOfSubject($cs->subject_id);

            
            $mentionService = app(MentionService::class);

            return [
                'subject'     => $cs->subject,
                'teacher'     => $cs->teacher,
                'coefficient' => $coefficient,
                'interros'    => collect(['interro1', 'interro2', 'interro3', 'interro4'])
                                    ->mapWithKeys(fn ($t) => [$t => $studentMarks[$t]['value'] ?? null]),
                'devoirs'     => collect($devoirColumns)
                                    ->mapWithKeys(fn ($t) => [$t => $studentMarks[$t]['value'] ?? null]),
                'moy_interro' => SubjectAverageCalculator::moyInterro($studentMarks),
                'moy_devoirs' => SubjectAverageCalculator::moyDevoirs($studentMarks, $devoirColumns),
                'moy'         => $moy,
                'moy_coef'    => SubjectAverageCalculator::moyCoef($moy, $coefficient),
                'mention'     => $mentionService->forValue($moy),
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


}
