<?php

namespace App\Livewire\Tenants\Users\Parent;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Services\MarksServices\ClasseAveragesCacheService;
use App\Services\MarksServices\ClasseSubjectMarksCacheService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ParentStudentsSkillsHeaderComponent extends Component
{
    use WireUiActions;

    public $counter = 0;

    public ?Student $student = null;

    public ?int $period = null;

    public function mount()
    {
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
        $this->counter++;
    }


    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
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
    
    public function render()
    {
        return view('livewire.tenants.users.parent.parent-students-skills-header-component');
    }
}
