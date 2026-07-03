<?php

namespace App\Livewire\Tenants\Filiars;

use App\Events\DataUpdatedEvent;
use App\Models\Filiar;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\YearlyFiliarChief;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Edition des chefs d'atelier de la filière")]
class ManageFiliarChiefsComponent extends Component
{
    use WireUiActions;


    public Filiar $filiar;

    public ?int $principalId = null;
    public ?int $adjointId = null;

    protected ?SchoolYear $activeSchoolYear = null;

    public function mount(string $filiar_slug): void
    {
        if (! $filiar_slug) abort(404);

        $filiar = Filiar::withTrashed()->where('slug', $filiar_slug)->first();

        if (! $filiar) abort(404);

        $this->filiar = $filiar;

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

        $chiefs = YearlyFiliarChief::query()
            ->where('filiar_id', $this->filiar->id)
            ->where('school_year_id', $schoolYear->id)
            ->where('is_active', true)
            ->get();

        $this->principalId = $chiefs->firstWhere('is_master', true)?->teacher_id;
        $this->adjointId = $chiefs->firstWhere('is_master', false)?->teacher_id;
    }

    #[Computed]
    public function teachers()
    {
        if ($this->filiar) {
            return $this->filiar->getFiliarTeachersOfSchoolYear()
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
            $this->addError('principalId', "Cet enseignant est déjà CA adjoint. Il ne peut pas cumuler les deux rôles.");
            $this->principalId = null;
        }
    }

    public function updatedAdjointId($value): void
    {
        $this->resetErrorBag('adjointId');

        if ($value && (int) $value === (int) $this->principalId) {
            $this->addError('adjointId', "Cet enseignant est déjà CA principal. Il ne peut pas cumuler les deux rôles.");
            $this->adjointId = null;
        }
    }

    public function save(): void
    {
        if (! $this->activeSchoolYear()) {
            $this->notification()->error(
                title: 'Aucune année active',
                description: "Impossible de définir les CA sans année scolaire active.",
            );
            return;
        }

        if (! $this->principalId && ! $this->adjointId) {
            $this->notification()->error(
                title: 'Aucune sélection effectuée',
                description: "Impossible de sauvegarder si vous n'avez choisir les CA",
            );
            return;
        }

        $this->dispatch('swal',
            icon: 'question',
            title: 'Confirmer les CA de la filière ?',
            text: "Les CA principal et adjoint de « {$this->filiar->name} » seront mis à jour pour l'année en cours.",
            showCancelButton: true,
            confirmButtonText: 'Confirmer',
            cancelButtonText: 'Annuler',
            onConfirmed: 'ConfirmSaveFiliarChiefs',
        );
    }

    #[On('ConfirmSaveFiliarChiefs')]
    public function confirmSaveFiliarChiefs(): void
    {
        $schoolYear = $this->activeSchoolYear();

        if (! $schoolYear) {
            return;
        }

        if ($this->principalId && (int) $this->principalId === (int) $this->adjointId) {
            $this->notification()->error(
                title: 'Erreur',
                description: "Le CA principal et le CA adjoint doivent être deux enseignants différents.",
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
            title: 'POSTES DES CA MIS A JOUR',
            description: "Les responsables de la filière ont été enregistrés.",
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

                $record = YearlyFiliarChief::where('filiar_id', $this->filiar->id)
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

                $record = YearlyFiliarChief::where('filiar_id', $this->filiar->id)
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
            description: "Les postes CA ont été libérés",
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
            $record = YearlyFiliarChief::where('filiar_id', $this->filiar->id)
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
            description: "Le poste CA " . $type . " a été libéré",
        );

        
    }


    protected function currentActiveIds(int $schoolYearId): array
    {
        $chiefs = YearlyFiliarChief::where('filiar_id', $this->filiar->id)
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
                description: "Enseigant introuvable!",
            );
            return;
        }

        if($teacher->hasCurrentlyAERole() || $teacher->hasCurrentlyPPRole()){

            $this->notification()->error(
                title: 'ERREUR CUMULE DE POSTE',
                description: "L'enseignant " . $teacher->getFullName() . " a déjà un poste de PP ou de AE!",
            );

            return;
        }

        if ($previousTeacherId) {
            $old = YearlyFiliarChief::where('filiar_id', $this->filiar->id)
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



        $record = YearlyFiliarChief::where('filiar_id', $this->filiar->id)
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
            YearlyFiliarChief::create([
                'filiar_id' => $this->filiar->id,
                'teacher_id' => $teacherId,
                'school_year_id' => $schoolYearId,
                'is_master' => $isMaster,
                'order' => $order,
                'is_active' => true,
            ]);
        }
    }

    protected function destroyOlder(YearlyFiliarChief $chief): void
    {
        
        $chief->delete();
        
    }

    public function render()
    {
        return view('livewire.tenants.filiars.manage-filiar-chiefs-component');
    }
}