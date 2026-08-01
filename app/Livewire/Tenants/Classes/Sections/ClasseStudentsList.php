<?php

namespace App\Livewire\Tenants\Classes\Sections;

use App\Jobs\JobToGeneratePrintableStudentsDataForThePrintViewComponent;
use App\Livewire\Tenants\ActionsTraits\StudentsActions;
use App\Models\Classe;
use App\Models\SchoolYear;
use App\Services\StudentsServices\StudentPrintColumns;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class ClasseStudentsList extends Component
{
    use WireUiActions, WithPagination, StudentsActions;

    public string  $classroom;
    public ?Classe $classe;
    public int     $perpage  = 30;
    public int     $counterh  = 30;

    public string $studentTypesActivesOrNotTargeted = 'onlyActives';

    public string $studentsTypesWithOrWithoutClasses = 'onlyHasClasse';

    public string $trashedStatus = 'withoutTrashed';

    public ?string $status = null;

    public int $counter = 0;

    public array $studentsTypesActivesOrNot = [
        'onlyActives' => "Seulement apprenants actifs",
        'onlyLeaves' => "Seulement apprenants déclarés abandons",
        'withLeaves' => "Tous les apprenants abandons inclus",
    ];

    public array $studentsWithOrWithoutClasses = [
        'onlyHasClasse' => "Seulement apprenants ayant de classes",
        'onlyHasntClasse' => "Seulement apprenants n'ayant pas de classe",
        'withHasntClasse' => "Tous les apprenants ayant ou pas pas de classe",
    ];
    
    public array $trashedStatuses = [
        'onlyTrashed' => "Uniquement les apprenants de la corbeille",
        'withoutTrashed' => "Tous les apprenants qui ne sont pas dans la corbeille",
        'withTrashed' => "Tous les apprenants y compris ceux de la corbeille",
    ];

    public array $defaultColumns = [
        ['key' => 'educMaster',  'label' => 'EducMaster',   'type' => 'text',  'position'   => 1],
        ['key' => 'full_name',   'label' => 'Nom & Prénom', 'type' => 'text',  'position'   => 2],
        ['key' => 'gender',      'label' => 'Sexe',         'type' => 'gender',  'position' => 3],
        ['key' => 'contacts',    'label' => 'Contact',      'type' => 'phone', 'position'   => 4],
        ['key' => 'status',      'label' => 'Statut',       'type' => 'badge', 'position'   => 5],
        ['key' => 'observations','label' => 'Obs.',         'type' => 'text', 'position'    => 6],
    ];




    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedGender(): void { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->reset(['search', 'gender']);
        $this->resetPage();
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata(): void
    {
        $this->counterh++;
    }

    protected function currentFilterConfig(): array
    {
        return [
            "trashedConfig"     => $this->trashedStatus,
            "leavesConfig"      => $this->studentTypesActivesOrNotTargeted,
            "hasClasseConfig"   => $this->studentsTypesWithOrWithoutClasses,
            "classe_id"         => $this->classe->id,
            "filiar_id"         => null,
            "serial_id"         => null,
            "promotion_id"      => null,
            "promotionInGroups" => null,
            "gender"            => null,
            "city"              => null,
            "department"        => null,
        ];
    }

    public function generateNewClasseStudentsList()
    {
        if (! $this->students->total()) {
            $this->notification()->info(
                title: "La procédure ne peut être lancée : aucun enregistrement trouvé",
                description: "Pour les conditions que vous avez définies, aucun enregistrement n'a été trouvé dans la base de données!",
            );
            return;
        }

        $schoolYear = SchoolYear::current()->first();

        if(!$schoolYear) return $this->notification()->error(title: "La procédure ne peut être lancée : aucune année scolaire active" );

        $config = array_merge([
            'tableColumns' => StudentPrintColumns::resolve($this->defaultColumns)
        ], $this->currentFilterConfig());

        JobToGeneratePrintableStudentsDataForThePrintViewComponent::dispatch(
            tenantId: tenant('id'),
            notifiableId: auth('tenant')->user()->id,
            docTitle: 'liste apprenants de la classe de ' . $this->classe->name,
            school_year_id: $schoolYear->id,
            config: $config,
        );
    }

    
    // ─── Render ───────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.tenants.classes.sections.classe-students-list');
    }
}
