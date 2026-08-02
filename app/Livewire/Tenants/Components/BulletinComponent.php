<?php

namespace App\Livewire\Tenants\Components;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Services\ClassesServices\ClasseEffectifsService;
use App\Services\MarksServices\ClasseAveragesCacheService;
use App\Services\MarksServices\ClasseSubjectMarksCacheService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class BulletinComponent extends Component
{
    public ?int $student_id = null;

    public ?int $period = null;

    public ?string $school_year_selected;

    public ?Classe $classe = null;

    public ?Student $student = null;

    public int $counter = 0;


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
    public function periods_types()
    {
        return $this->activeYear->getPeriods();
    }


    public function updatedPeriod(?string $period)
    {
        session()->put('tenant_student_bulletin_period', $period);

        $this->dispatch("ReloadForNewStudent", $this->period, $this->student->id, $this->currentClasse->id);

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

    
    public function render()
    {
        return view('livewire.tenants.components.bulletin-component');
    }
}
