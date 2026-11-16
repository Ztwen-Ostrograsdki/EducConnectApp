<?php

namespace App\Livewire\Tenants\Modules;

use App\Livewire\Tenants\ActionsTraits\ModuleClasseValidationActionsTrait;
use App\Models\Classe;
use App\Models\ClasseSubjectOfSchoolYear;
use App\Models\ModuleClasseValidation;
use App\Models\SchoolYear;
use App\Models\Subject;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Seuils de validation des modules')]
class ManageModuleClasseValidationsComponent extends Component
{
    use ModuleClasseValidationActionsTrait;
    use WireUiActions;

    public ?int $selectedClasseId = null;

    public ?int $schoolYearId = null;

    public string $searchClasses = '';

    public string $searchModules = '';

    /** Édition inline : id de la ligne ModuleClasseValidation en cours d’édition */
    public ?int $editingValidationId = null;

    public ?float $editSeuil = null;

    public ?int $editCoef = null;

    /** Formulaire de création rapide pour un module sans config */
    public ?int $creatingForModuleId = null;

    public float $createSeuil = 10;

    public ?int $createCoef = null;

    protected $queryString = [
        'selectedClasseId' => ['except' => null, 'as' => 'classe'],
    ];

    public function mount(): void
    {
        $this->schoolYearId = $this->getActiveSchoolYearId();

        if(session()->has('selected_classe_validation') && session('selected_classe_validation')){

            $this->selectedClasseId = (int) session('selected_classe_validation');

            self::selectClasse($this->selectedClasseId);
        }
    }

    public function updatedSelectedClasseId(): void
    {
        $this->editingValidationId = null;
        $this->creatingForModuleId = null;
        $this->searchModules = '';
        $this->refreshModuleClasseValidationsList();
    }

    public function updatedSchoolYearId(): void
    {
        $this->editingValidationId = null;
        $this->creatingForModuleId = null;
        $this->refreshModuleClasseValidationsList();
    }

    // ─── Computed ─────────────────────────────────────────────────────

    #[Computed]
    public function activeSchoolYear(): ?SchoolYear
    {
        return SchoolYear::current()?->first();
    }

    #[Computed]
    public function classes()
    {
        $yearId = $this->schoolYearId ?? $this->getActiveSchoolYearId();

        if (! $yearId) {
            return collect();
        }

        return Classe::query()
            ->where('is_active', true)
            ->where('is_new_system', true)
            ->where('school_year_id', $yearId)
            ->when($this->searchClasses, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchClasses . '%')
                    ->orWhere('code', 'like', '%' . $this->searchClasses . '%');
            }))
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function selectedClasse(): ?Classe
    {
        if (! $this->selectedClasseId) {
            return null;
        }

        return Classe::query()->where('id', $this->selectedClasseId)->first();
    }

    /**
     * Modules (Subject is_module=true) assignés à la classe sélectionnée
     * pour l’année scolaire courante, avec leur éventuelle config de validation.
     */
    #[Computed]
    public function modulesForClasse()
    {
        if (! $this->selectedClasseId) {
            return collect();
        }

        $yearId = $this->schoolYearId ?? $this->getActiveSchoolYearId();

        if (! $yearId) {
            return collect();
        }

        $moduleIds = ClasseSubjectOfSchoolYear::query()
            ->where('classe_id', $this->selectedClasseId)
            ->where('school_year_id', $yearId)
            ->where('is_active', true)
            ->whereNull('ended_at')
            ->pluck('subject_id')
            ->unique()
            ->values();

        if ($moduleIds->isEmpty()) {
            return collect();
        }

        $modules = Subject::query()
            ->module()
            ->active()
            ->whereIn('id', $moduleIds)
            ->when($this->searchModules, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchModules . '%')
                    ->orWhere('code', 'like', '%' . $this->searchModules . '%');
            }))
            ->orderBy('name')
            ->get();

        $validations = ModuleClasseValidation::query()
            ->where('school_year_id', $yearId)
            ->where('classe_id', $this->selectedClasseId)
            ->whereIn('module_id', $modules->pluck('id'))
            ->get()
            ->keyBy('module_id');

        return $modules->map(function (Subject $module) use ($validations) {
            $module->setRelation('validationConfig', $validations->get($module->id));

            return $module;
        });
    }

    // ─── Actions UI ───────────────────────────────────────────────────

    public function selectClasse(int $classeId): void
    {
        session()->put('selected_classe_validation', $classeId);
        
        $this->selectedClasseId = $classeId;
        $this->updatedSelectedClasseId();
    }

    public function startEdit(int $validationId): void
    {
        $row = $this->findModuleClasseValidationForManage($validationId);

        if (! $row) {
            return;
        }

        $this->editingValidationId = $validationId;
        $this->editSeuil = (float) $row->seuil;
        $this->editCoef = $row->coef;
        $this->creatingForModuleId = null;
    }

    public function cancelEdit(): void
    {
        $this->editingValidationId = null;
        $this->editSeuil = null;
        $this->editCoef = null;
    }

    public function saveEdit(): void
    {
        if (! $this->editingValidationId) {
            return;
        }

        $this->updateModuleClasseValidation($this->editingValidationId, [
            'seuil' => $this->editSeuil,
            'coef'  => $this->editCoef,
        ]);

        $this->cancelEdit();
    }

    public function startCreate(int $moduleId): void
    {
        $this->creatingForModuleId = $moduleId;
        $this->createSeuil = 10;
        $this->createCoef = null;
        $this->editingValidationId = null;
    }

    public function cancelCreate(): void
    {
        $this->creatingForModuleId = null;
        $this->createSeuil = 10;
        $this->createCoef = null;
    }

    public function saveCreate(): void
    {
        if (! $this->creatingForModuleId || ! $this->selectedClasseId) {
            return;
        }

        $this->saveModuleClasseValidation(
            $this->creatingForModuleId,
            $this->selectedClasseId,
            $this->schoolYearId,
            [
                'seuil' => $this->createSeuil,
                'coef'  => $this->createCoef,
            ]
        );

        $this->cancelCreate();
    }

    public function render()
    {
        return view('livewire.tenants.modules.manage-module-classe-validations-component');
    }
}
