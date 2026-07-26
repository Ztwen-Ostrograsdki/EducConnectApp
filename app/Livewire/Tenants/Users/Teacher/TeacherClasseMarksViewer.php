<?php

namespace App\Livewire\Tenants\Users\Teacher;

use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Subject;
use App\Services\ClassesServices\ClasseEffectifsService;
use App\Services\MarksServices\ClasseSubjectMarksCacheService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class TeacherClasseMarksViewer extends Component
{
    use WireUiActions;

    protected const INTERRO_TYPES = ['interro1', 'interro2', 'interro3', 'interro4'];

    public $counter = 0;

    public string $classe_slug;

    public string $subject_slug;

    public int $classe_subject_id;

    public ?int $period;

    public function mount(string $classe_slug, string $subject_slug)
    {
        if(!$classe_slug && !$subject_slug) return abort(404);

        $this->classe_slug  = $classe_slug;

        $this->subject_slug  = $subject_slug;

        $this->classe_subject_id = $this->classe_subject->id;

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

    public function updatedPeriod()
    {
        unset($this->marksData);
    }

    /**
     * Placeholder pour le moment : ouvrira un modal d'édition des notes de l'apprenant.
     */
    public function editStudentMark(int $student_id)
    {
        // À implémenter : ouverture du formulaire/modal d'édition des notes.
    }

    #[Computed]
    public function teacher()
    {
        return auth('tenant')->user()->teacher;
    }

    #[Computed]
    public function classe_subject()
    {
        $classe_subject = ClasseSubjectOfSchoolYear::with(['teacher', 'classe', 'subject'])
                   ->whereHas('teacher', fn($qt) =>
                        $qt->where('id', $this->teacher->id)
                   )
                   ->whereHas('classe', fn($qc) =>
                        $qc->where('slug', $this->classe_slug)
                   )
                   ->whereHas('subject', fn($qs) =>
                        $qs->where('slug', $this->subject_slug)
                )->where('school_year_id', $this->activeYear->id)->whereNull('ended_at')->where('is_active', true)->first();

        if(!$classe_subject) return abort(404);

        return $classe_subject;
    }

    #[Computed]
    public function classe()
    {
        if(!$this->classe_slug) return abort(404);

        $classe = Classe::firstWhere('slug', $this->classe_slug);

        if(!$classe) return abort(404);

        return $classe;
    }

    #[Computed]
    public function effectifs()
    {
        $effectifs = app(ClasseEffectifsService::class)->getEffectifs($this->classe->id);

        return $effectifs;
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
        if(!$this->subject_slug) return abort(404);

        $subject = Subject::firstWhere('slug', $this->subject_slug);

        if(!$subject) return abort(404);

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


    /**
     * Notes de la classe pour cette matière/période, lues depuis le cache
     * (App\Services\ClasseSubjectMarksCacheService), sans requête directe sur Mark.
     */
    #[Computed]
    public function marksData(): array
    {
        if (!$this->period) {
            return [];
        }

        return app(ClasseSubjectMarksCacheService::class)->get(
            $this->classe->id,
            $this->subject->id,
            $this->period,
            $this->activeYear->id
        );
    }


    /**
     * Fusionne la liste des apprenants avec leurs notes en cache, et calcule
     * moy. interro, moy., moy. coef., et rang localement (pas de requête supplémentaire).
     */
    #[Computed]
    public function studentsRows(): array
    {
        $columns = array_keys($this->markColumns);
        $devoirColumns = array_keys($this->devoirColumns());
        $coefficient = (float) ($this->classe_subject->coefficient ?? 0);

        $rows = $this->students->map(function (Student $student) use ($columns, $devoirColumns, $coefficient) {

            $studentMarks = $this->marksData[$student->id] ?? [];

            $values = [];

            foreach ($columns as $type) {
                $values[$type] = $studentMarks[$type]['value'] ?? null;
            }

            // Moy. Interro : moyenne des interros réellement saisies (ignore les vides).
            $interroValues = array_filter(
                array_intersect_key($values, array_flip(self::INTERRO_TYPES)),
                fn ($v) => !is_null($v)
            );

            $moyInterro = !empty($interroValues)
                ? round(array_sum($interroValues) / count($interroValues), 2)
                : null;

            // Moy. Devoirs : moyenne des devoirs réellement saisis (1 ou 2 selon le tenant).
            $devoirValues = array_filter(
                array_intersect_key($values, array_flip($devoirColumns)),
                fn ($v) => !is_null($v)
            );

            $moyDevoirs = !empty($devoirValues)
                ? round(array_sum($devoirValues) / count($devoirValues), 2)
                : null;

            // Moy. générale : moyenne de moyInterro et moyDevoirs si les deux existent,
            // sinon celle qui existe (parmi les deux), sinon null.
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

        // Réordonne selon l'ordre alphabétique d'origine pour l'affichage,
        // le rang calculé au-dessus reste correct.
        return $ranked->sortBy(fn ($row) => $row['student']->name . $row['student']->prenames)->values()->all();
    }

    /**
     * Les colonnes "devoirs" seules (sans les interros), pour isoler leur moyenne.
     */
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

    public function render()
    {
        return view('livewire.tenants.users.teacher.teacher-classe-marks-viewer');
    }
}