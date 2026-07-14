<?php

namespace App\Livewire\Tenants\Students;

use App\Jobs\JobToGeneratePrintableStudentsDataForThePrintViewComponent;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use App\Services\StudentsServices\StudentPrintColumns;
use App\Services\StudentsServices\StudentPrintQuery;
use App\Tools\BeninData;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Title("Page de gestion des impression de la liste des apprenants")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class StudentsPrintsManagerComponent extends Component
{
    use WireUiActions;

    public ?string $classe_slug = null;
    public ?string $filiar_slug = null;
    public ?string $serial_slug = null;
    public ?string $promotion_slug = null;
    
    public string $studentTypesActivesOrNotTargeted = 'onlyActives';

    public string $studentsTypesWithOrWithoutClasses = 'onlyHasClasse';

    public string $trashedStatus = 'withoutTrashed';

    public ?string $city = null;

    public ?string $gender = null;

    public ?string $department = null;

    public ?string $status = null;

    public ?int $filiar_id = null;

    public ?int $serial_id = null;

    public ?int $promotion_id = null;

    public ?string $promotionInGroups = null;

    public ?int $classe_id = null;

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

    protected string $sessionKey = 'student-list-selected-columns';

    public array $defaultColumns = [
        ['key' => 'full_name',        'label' => 'Nom complet',      'position' => 1, 'type' => 'text'],
        ['key' => 'gender',           'label' => 'Sexe',             'position' => 2, 'type' => 'text'],
        ['key' => 'educMaster',       'label' => 'EducMaster',       'position' => 3, 'type' => 'text'],
        ['key' => 'birth_date',       'label' => 'Date naiss./Age',  'position' => 4, 'type' => 'age'],
        ['key' => 'classe.name',      'label' => 'Classe',           'position' => 5, 'type' => 'text'],
        ['key' => 'contacts',         'label' => 'Contacts',         'position' => 6, 'type' => 'phone'],
        ['key' => 'observation',      'label' => 'OBS',              'position' => 7, 'type' => 'text'],
    ];

    public array $selectedColumns = [];

    public function mount(?string $classe_slug = null)
    {
        $this->selectedColumns = session()->get($this->sessionKey, []);

        $this->selectedColumns = array_values(array_filter(
            $this->selectedColumns,
            fn (string $key) => array_key_exists($key, $this->availableColumns)
        ));

        if($classe_slug){

            $classe = Classe::firstWhere('slug', $classe_slug);

            if(!$classe) return abort(404);

            $this->classe_id = $classe->id;

            $this->updatedClasseId($classe->id);
        }

        if (session()->has('print_students_trashed_status')) {
            $this->trashedStatus = session('print_students_trashed_status');
        }
        if (session()->has('print_students_leaves_status')) {
            $this->studentTypesActivesOrNotTargeted = session('print_students_leaves_status');
        }
        if (session()->has('print_students_has_classe_status')) {
            $this->studentsTypesWithOrWithoutClasses = session('print_students_has_classe_status');
        }

        if(session()->has('print_students_status_selected')){

            $this->status = session('print_students_status_selected');
        }

        if(session()->has('print_students_classe_selected')){

            $this->classe_id = session('print_students_classe_selected');
        }

        if(session()->has('print_students_filiar_selected')){

            $this->filiar_id = session('print_students_filiar_selected');
        }

        if(session()->has('print_students_promotion_selected')){

            $this->promotion_id = session('print_students_promotion_selected');
        } 
        
        if(session()->has('print_students_promotions_grouped_selected')){

            $this->promotionInGroups = session('print_students_promotions_grouped_selected');
        }

        if(session()->has('print_students_gender_selected')){

            $this->gender = session('print_students_gender_selected');
        }

        if(session()->has('print_students_city_selected')){

            $this->city = session('print_students_city_selected');
        }

        if(session()->has('print_students_department_selected')){

            $this->department = session('print_students_department_selected');
        }

        if(session()->has('print_students_serial_selected')){

            $this->serial_id = session('print_students_serial_selected');
        }
    }


    public function restoreSelects(): void
    {
        $this->selectedColumns = [];

        $this->persistSelection();

    }


    protected function persistSelection(): void
    {
        session()->put($this->sessionKey, $this->selectedColumns);
    }

    public function toggleColumn(string $key): void
    {
        $index = array_search($key, $this->selectedColumns, true);

        if ($index !== false) {
            unset($this->selectedColumns[$index]);
            $this->selectedColumns = array_values($this->selectedColumns);
        } else {
            $this->selectedColumns[] = $key;
        }

        $this->persistSelection();
    }

    #[Computed]
    public function orderedColumns(): array
    {
        return collect($this->selectedColumns)
            ->mapWithKeys(fn (string $key) => [$key => $this->availableColumns[$key]['label'] ?? $key])
            ->toArray();
    }



    public function resetFilters()
    {
        session()->forget(
            [
                'print_students_city_selected', 
                'print_students_department_selected', 
                'print_students_gender_selected', 
                'print_students_promotion_selected', 
                'print_students_promotions_grouped_selected', 
                'print_students_classe_selected',
                'print_students_filiar_selected',
                'print_students_serial_selected', 
                'print_students_status_selected',
                'print_students_trashed_status',
                'print_students_leaves_status',
                'print_students_has_classe_status',

            ]
        );

        $this->reset('city', 'gender', 'department', 'classe_id', 'promotion_id', 'promotionInGroups', 'filiar_id', 'serial_id', 'trashedStatus', 'studentsTypesWithOrWithoutClasses', 'studentTypesActivesOrNotTargeted');
    }


    #[Computed]
    public function filiars()
    {
        return Filiar::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function serials()
    {
        return Serial::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function classes()
    {
        return Classe::where('classes.school_year_id', $this->activeYear->id)->where('classes.is_active', true)->where('classes.is_locked', false)->orderBy('name', 'desc')->get();
    }

    #[Computed]
    public function subjects()
    {
        return Subject::where('is_active', true)->orderBy('name', 'desc')->get();
    }
    
    #[Computed]
    public function genders() : ?array 
    {
        return config('app.genders');

    }

    #[Computed]
    public function departments() : ?array 
    {
        return BeninData::getDepartments();

    }

    #[Computed]
    public function cities() : ?array
    {
        return array_values(array_unique(array_merge(...BeninData::getCities())));
    }

    #[Computed]
    public function promotions()
    {
        return Promotion::where('is_active', true)->orderBy('name', 'desc')->get();
    }
    
    #[Computed]
    public function promotionsGrouped()
    {
        return array_unique(Promotion::where('is_active', true)->orderBy('name', 'asc')->pluck('name')->toArray());
    }

    public function updatedTrashedStatus(?string $value): void
    {
        session()->put('print_students_trashed_status', $value);
    }

    public function updatedStudentTypesActivesOrNotTargeted(?string $value): void
    {
        session()->put('print_students_leaves_status', $value);
    }

    public function updatedStudentsTypesWithOrWithoutClasses(?string $value): void
    {
        session()->put('print_students_has_classe_status', $value);
    }


    public function updatedDepartment(?string $value): void
    {
        session()->put('print_students_department_selected', $value);
    }


    public function updatedCity(?string $value): void
    {
        session()->put('print_students_city_selected', $value);
    }


    public function updatedGender(?string $value): void
    {
        session()->put('print_students_gender_selected', $value);
    }


    public function updatedStatus(?string $value): void
    {
        session()->put('print_students_status_selected', $value);
    }


    public function updatedClasseId(?string $value): void
    {
        if($value) {

            $this->reset(['serial_id', 'filiar_id', 'promotion_id', 'promotionInGroups', 'studentsTypesWithOrWithoutClasses']);

            session()->forget(
                [
                    'print_students_serial_selected',
                    'print_students_filiar_selected',
                    'print_students_promotions_grouped_selected',
                    'print_students_promotion_selected',
                ]
            );

        }
        session()->put('print_students_classe_selected', $value);
        
        session()->put('print_students_has_classe_status', $this->studentsTypesWithOrWithoutClasses);
    }


    public function updatedSubjectId(?string $value): void
    {
        session()->put('print_students_subject_selected', $value);
    }


    public function updatedPromotionId(?string $value): void
    {
        if($value) {

            $this->reset(['filiar_id', 'serial_id', 'classe_id', 'promotionInGroups']);

            session()->forget(
                [
                    'print_students_promotions_grouped_selected', 
                    'print_students_classe_selected',
                    'print_students_filiar_selected',
                    'print_students_serial_selected', 
                ]
            );

        }

        session()->put('print_students_promotion_selected', $value);
    }
    
    public function updatedPromotionInGroups(?string $value): void
    {
        if($value) {

            $this->reset(['classe_id', 'promotion_id']);

            session()->forget(
                [
                    'print_students_classe_selected',
                    'print_students_promotion_selected',
                ]
            );

        }
        session()->put('print_students_promotions_grouped_selected', $value);
    }
    

    public function updatedFiliarId(?string $value): void
    {
        if($value) {

            $this->reset(['serial_id', 'promotion_id', 'classe_id']);

            session()->forget(
                [
                    'print_students_classe_selected',
                    'print_students_serial_selected',
                    'print_students_promotion_selected',
                ]
            );

        }
        session()->put('print_students_filiar_selected', $value);
    }


    public function updatedSerialId(?string $value): void
    {
        if($value) {

            $this->reset(['filiar_id', 'promotion_id', 'classe_id']);

            session()->forget(
                [
                    'print_students_classe_selected',
                    'print_students_filiar_selected',
                    'print_students_promotion_selected',
                ]
            );

        }
        session()->put('print_students_serial_selected', $value);
    }


    #[On("StudentDataUpdatedEventLiveEvent")]
    public function studentDataUpdated()
    {
        $this->onReloadDashboard();
    }
    
    #[On("StudentsPDFCompletedSuccessfullyLiveEvent")]
    public function pdfUpdated()
    {
        $this->onReloadDashboard();
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

    #[Computed]
    public function activesStudentsCounter()
    {
        return  $this->getStudentsCounter()->where('status', 'active')->count();
    }
    
    #[Computed]
    public function allStudentsCounter()
    {
        return $this->getStudentsCounter()->count();
    }

    #[Computed]
    public function availableColumns(): array
    {
        return StudentPrintColumns::$columns;
    }



    public function getStudentsCounter()
    {
        return StudentPrintQuery::build($this->currentFilterConfig(), $this->activeYear->id)->withTrashed();
    }

    protected function currentFilterConfig(): array
    {
        return [
            "trashedConfig"     => $this->trashedStatus,
            "leavesConfig"      => $this->studentTypesActivesOrNotTargeted,
            "hasClasseConfig"   => $this->studentsTypesWithOrWithoutClasses,
            "classe_id"         => $this->classe_id,
            "filiar_id"         => $this->filiar_id,
            "serial_id"         => $this->serial_id,
            "promotion_id"      => $this->promotion_id,
            "promotionInGroups" => $this->promotionInGroups,
            "gender"            => $this->gender,
            "city"              => $this->city,
            "department"        => $this->department,
        ];
    }

    public function initPrintProcess()
    {
        if (! $this->allStudentsCounter) {
            $this->notification()->info(
                title: "La procédure ne peut être lancée : aucun enregistrement trouvé",
                description: "Pour les conditions que vous avez définies, aucun enregistrement n'a été trouvé dans la base de données!",
            );
            return;
        }

        $config = array_merge([
            'tableColumns' => StudentPrintColumns::resolve($this->selectedColumns
                ? StudentPrintColumns::build($this->selectedColumns)
                : null
            )
        ], $this->currentFilterConfig());

        JobToGeneratePrintableStudentsDataForThePrintViewComponent::dispatch(
            tenantId: tenant('id'),
            notifiableId: auth('tenant')->user()->id,
            docTitle: 'liste apprenants',
            school_year_id: $this->activeYear->id,
            config: $config,
        );
    }



    public function render()
    {
        return view('livewire.tenants.students.students-prints-manager-component');
    }
}
