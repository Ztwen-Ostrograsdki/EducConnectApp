<?php

namespace App\Livewire\Tenants\Subjects;


use App\Events\DataUpdatedEvent;
use App\Models\SchoolYear;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\YearlySubjectChief;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Edition des AE des matières")]
class ManageSubjectChiefsComponent extends Component
{
    use WireUiActions;


    public Subject $subject;

    public ?int $principalId = null;
    public ?int $adjointId = null;

    protected ?SchoolYear $activeSchoolYear = null;

    public function mount(string $subject_slug): void
    {
        if (! $subject_slug) abort(404);

        $subject = Subject::withTrashed()->where('slug', $subject_slug)->first();

        if (! $subject) abort(404);

        $this->subject = $subject;

        $this->loadCurrentChiefs();
    }

    protected function activeSchoolYear(): ?SchoolYear
    {
        return $this->activeSchoolYear ??= SchoolYear::where('is_active', true)->first();
    }

    protected function loadCurrentChiefs(): void
    {
        $schoolYear = $this->activeSchoolYear();

        if (! $schoolYear) {
            return;
        }

        $chiefs = YearlySubjectChief::query()
            ->where('subject_id', $this->subject->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('is_active', true)
            ->get();

        $this->principalId = $chiefs->firstWhere('is_master', true)?->teacher_id;
        $this->adjointId = $chiefs->firstWhere('is_master', false)?->teacher_id;
    }

    #[Computed]
    public function teachers()
    {
        if ($this->subject) {
            return $this->subject->getSubjectTeachersOfSchoolYear()
                ->orderBy('users.name')
                ->orderBy('users.prenames')
                ->get();
        }

        return collect();
    }

    /**
     * Enseignants disponibles pour les selects : on exclut ceux qui occupent déjà
     * un poste de CA (principal ou adjoint) dans la section "CA actifs".
     */
    #[Computed]
    public function availableTeachers()
    {
        $excludedIds = array_filter([$this->principalId, $this->adjointId]);

        return $this->teachers->reject(
            fn ($teacher) => in_array($teacher->id, $excludedIds)
        );
    }

    #[Computed]
    public function principalTeacher()
    {
        return $this->principalId
            ? $this->teachers->firstWhere('id', $this->principalId)
            : null;
    }

    #[Computed]
    public function adjointTeacher()
    {
        return $this->adjointId
            ? $this->teachers->firstWhere('id', $this->adjointId)
            : null;
    }

    public function updatedPrincipalId($value): void
    {
        $this->resetErrorBag('principalId');

        if ($value && (int) $value === (int) $this->adjointId) {
            $this->addError('principalId', "Cet enseignant est déjà AE adjoint. Il ne peut pas cumuler les deux rôles.");
            $this->principalId = null;
        }
    }

    public function updatedAdjointId($value): void
    {
        $this->resetErrorBag('adjointId');

        if ($value && (int) $value === (int) $this->principalId) {
            $this->addError('adjointId', "Cet enseignant est déjà AE principal. Il ne peut pas cumuler les deux rôles.");
            $this->adjointId = null;
        }
    }

    public function save(): void
    {
        if (! $this->activeSchoolYear()) {
            $this->notification()->error(
                title: 'Aucune année active',
                description: "Impossible de définir les AE sans année scolaire active.",
            );
            return;
        }

        if (! $this->principalId && ! $this->adjointId) {
            $this->notification()->error(
                title: 'Aucune sélection effectuée',
                description: "Impossible de sauvegarder si vous n'avez choisir les AE",
            );
            return;
        }

        $this->dispatch('swal',
            icon: 'question',
            title: 'Confirmer les AE de la matière ?',
            text: "Les AE principal et adjoint de « {$this->subject->name} » seront mis à jour pour l'année en cours.",
            showCancelButton: true,
            confirmButtonText: 'Confirmer',
            cancelButtonText: 'Annuler',
            onConfirmed: 'ConfirmSaveSubjectChiefs',
        );
    }

    #[On('ConfirmSaveSubjectChiefs')]
    public function OnCconfirmSaveSubjectChiefs(): void
    {
        $schoolYear = $this->activeSchoolYear();

        if (! $schoolYear) {
            return;
        }

        if ($this->principalId && (int) $this->principalId === (int) $this->adjointId) {
            $this->notification()->error(
                title: 'Erreur',
                description: "Les AE principal et adjoint doivent être deux enseignants différents.",
            );
            return;
        }

        [$previousPrincipalId, $previousAdjointId] = $this->currentActiveIds($schoolYear->id);

        DB::transaction(function () use ($schoolYear, $previousPrincipalId, $previousAdjointId) {
            $this->syncSlot($this->principalId, true, 1, $previousPrincipalId, $schoolYear->id);
            $this->syncSlot($this->adjointId, false, 2, $previousAdjointId, $schoolYear->id);
        });

        unset($this->teachers, $this->availableTeachers, $this->principalTeacher, $this->adjointTeacher);
        $this->loadCurrentChiefs();

        $this->notification()->success(
            title: 'POSTES DES AE MIS A JOUR',
            description: "Les responsables de la matières ont été enregistrés.",
        );

        broadcast(new DataUpdatedEvent(tenant('id')));
    }

    public function removeChiefs()
    {
        if (!$this->principalId &&  !$this->adjointId) {
            return;
        }

        $schoolYear = $this->activeSchoolYear();

        if (! $schoolYear) {
            return;
        }

        if($this->principalId){

            $teacherId = $this->principalId;

            if ($teacherId) {

                $record = YearlySubjectChief::where('subject_id', $this->subject->id)
                    ->where('school_year_id', $schoolYear->id)
                    ->where('teacher_id', $teacherId)
                    ->where('is_master', true)
                    ->first();

                if ($record) {

                    $this->destroyOlder($record);
                }
            }
        }

        if($this->adjointId){

            $teacherId = $this->adjointId;

            if ($teacherId) {

                $record = YearlySubjectChief::where('subject_id', $this->subject->id)
                    ->where('school_year_id', $schoolYear->id)
                    ->where('teacher_id', $teacherId)
                    ->where('is_master', false)
                    ->first();

                if ($record) {
                    
                    $this->destroyOlder($record);
                }
            }
        }

        $this->principalId = null;

        $this->adjointId = null;

        unset($this->availableTeachers, $this->principalTeacher, $this->adjointTeacher);

        $this->notification()->success(
            title: 'Postes libérés',
            description: "Les postes AE ont été libérés",
        );
    }

    public function removeChief(string $type): void
    {
        $teacherId = $type === 'principal' ? $this->principalId : $this->adjointId;

        if (! $teacherId) {
            return;
        }

        $schoolYear = $this->activeSchoolYear();

        if (! $schoolYear) {
            return;
        }

        $isMaster = $type === 'principal';
        $teacherId = $isMaster ? $this->principalId : $this->adjointId;

        if ($teacherId) {
            $record = YearlySubjectChief::where('subject_id', $this->subject->id)
                ->where('school_year_id', $schoolYear->id)
                ->where('teacher_id', $teacherId)
                ->where('is_master', $isMaster)
                ->first();

            if ($record) {
                $this->destroyOlder($record);
            }
        }

        if ($isMaster) {
            $this->principalId = null;
        } else {
            $this->adjointId = null;
        }

        unset($this->availableTeachers, $this->principalTeacher, $this->adjointTeacher);

        $this->notification()->success(
            title: 'Poste libéré',
            description: "Le poste AE " . $type . " a été libéré",
        );

        
    }


    protected function currentActiveIds(int $schoolYearId): array
    {
        $chiefs = YearlySubjectChief::where('subject_id', $this->subject->id)
            ->where('school_year_id', $schoolYearId)
            ->where('is_active', true)
            ->get();

        return [
            $chiefs->firstWhere('is_master', true)?->teacher_id,
            $chiefs->firstWhere('is_master', false)?->teacher_id,
        ];
    }

    protected function syncSlot(?int $teacherId, bool $isMaster, int $order, ?int $previousTeacherId, int $schoolYearId): void
    {
        if ((int) $teacherId === (int) $previousTeacherId) {
            return;
        }

        $teacher = Teacher::find($teacherId);

        if(!$teacher){

            $this->notification()->error(
                title: 'Erreur',
                description: "Enseignant introuvable!",
            );
            return;
        }

        if($teacher->hasCurrentlyCARole() || $teacher->hasCurrentlyPPRole()){

            $this->notification()->error(
                title: 'ERREUR CUMULE DE POSTE',
                description: "L'enseignant " . $teacher->getFullName() . " a déjà un poste de CA ou de PP!",
            );

            return;
        }

        if ($previousTeacherId) {
            $old = YearlySubjectChief::where('subject_id', $this->subject->id)
                ->where('school_year_id', $schoolYearId)
                ->where('teacher_id', $previousTeacherId)
                ->where('is_master', $isMaster)
                ->first();

            if ($old) {
                $this->destroyOlder($old);
            }
        }

        if (! $teacherId) {
            return;
        }

        $record = YearlySubjectChief::where('subject_id', $this->subject->id)
            ->where('school_year_id', $schoolYearId)
            ->where('teacher_id', $teacherId)
            ->first();

        if ($record) {
            $record->update([
                'is_master' => $isMaster,
                'order' => $order,
                'is_active' => true,
            ]);
        } else {
            YearlySubjectChief::create([
                'subject_id' => $this->subject->id,
                'teacher_id' => $teacherId,
                'school_year_id' => $schoolYearId,
                'is_master' => $isMaster,
                'order' => $order,
                'is_active' => true,
            ]);
        }
    }

    protected function destroyOlder(YearlySubjectChief $chief): void
    {
        $chief->delete();
    }

    public function render()
    {
        return view('livewire.tenants.subjects.manage-subject-chiefs-component');
    }
}