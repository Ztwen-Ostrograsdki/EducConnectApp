<?php

namespace App\Livewire\Tenants\Students;

use App\Jobs\JobToGeneratePrintableStudentsDataForThePrintViewComponent;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Student;
use App\Models\Subject;
use App\Tools\BeninData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
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

    public array $columns = [
        'name' => "Nom et Prénoms",
        'sexe' => "Sexe",
        'educMaster' => "EducMaster",
        'matricule' => "Matricule",
        'pere' => "Père",
        'mere' => "Mère",
        'contacts' => "Contacts",
        'classe' => "Classe",
        'dateNaissance' => "Date de naissance - Age",
        'status' => "Statut",
        'observation' => "Observations",
    ];

    public array $defaultColumns = [
        'name' => "Nom et Prénoms",
        'sexe' => "Sexe",
        'educMaster' => "EducMaster",
        'classe' => "Classe",
        'dateNaissance' => "Date de naissance - Age",
        'status' => "Statut",
        'observation' => "Observations",
    ];

    public array $selectedColumns = [];

    public function mount(): void
    {
        $this->selectedColumns = session()->get($this->sessionKey, []);

        $this->selectedColumns = array_values(array_filter(
            $this->selectedColumns,
            fn (string $key) => array_key_exists($key, $this->columns)
        ));

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

    // Propriété calculée : ['name' => 'Nom et Prénoms', 'classe' => 'Classe', ...] dans l'ORDRE choisi
    #[Computed]
    public function orderedColumns(): array
    {
        return collect($this->selectedColumns)
            ->mapWithKeys(fn (string $key) => [$key => $this->columns[$key] ?? $key])
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
            ]
        );


        $this->reset('city', 'gender', 'department', 'classe_id', 'promotion_id', 'promotionInGroups', 'filiar_id', 'serial_id', 'status');
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

            $this->reset(['serial_id', 'filiar_id', 'promotion_id', 'promotionInGroups']);

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




    public function getStudentsCounter()
    {
        return Student::query()
        ->select('students.*')
        ->withTrashed()
        ->when($this->city, function (Builder $query) {
            $query->where('city', $this->city);
        })
        ->when($this->department, function (Builder $query) {
            $query->where('department', $this->department);
        })
        ->when($this->classe_id, function (Builder $query) {
            if($this->classe_id !== 'sans-classe'){
                $query->whereHas('classes', fn($q) => 
                    $q->where('is_active', true)->where('classe_id', $this->classe_id)->where('school_year_id', $this->activeYear->id)
                );
            }
            else{
                $query->whereDoesntHave('classes', fn($q) => 
                    $q->where('is_active', true)->where('school_year_id', $this->activeYear->id)
                );
            }
        })
        ->when($this->promotion_id, function (Builder $query) {
            $query->whereHas('classes', fn($q) => 
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereHas('classe', fn($qr) => 
                    $qr->where('promotion_id', $this->promotion_id)
                       ->where('is_active', true)
                       ->where('school_year_id', $this->activeYear->id)
                  )
            );
        })
        ->when($this->promotionInGroups, function (Builder $query) {
            $query->whereHas('classes', fn($q) => 
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereHas('classe', fn($qr0) => 
                    $qr0->whereHas('promotion', fn($qr) => 
                        $qr->where('name', $this->promotionInGroups)
                            ->where('is_active', true)
                            ->where('school_year_id', $this->activeYear->id)
                    )
                  )
            );
        })
        ->when($this->filiar_id, function (Builder $query) {
            $query->whereHas('classes', fn($q) => 
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereHas('classe', fn($qr) => 
                    $qr->where('filiar_id', $this->filiar_id)
                       ->where('is_active', true)
                       ->where('school_year_id', $this->activeYear->id)
                  )
            );
        })
        ->when($this->serial_id, function (Builder $query) {
            $query->whereHas('classes', fn($q) => 
                $q->where('is_active', true)
                  ->where('school_year_id', $this->activeYear->id)
                  ->whereHas('classe', fn($qr) => 
                    $qr->where('serial_id', $this->serial_id)
                       ->where('is_active', true)
                       ->where('school_year_id', $this->activeYear->id)
                  )
            );
        })
        ->when($this->gender, fn($q) => $q->whereIn('gender', [$this->gender, Str::lower($this->gender), Str::upper($this->gender)]));
        
    }


    public function initPrintProcess()
    {
        if(!$this->allStudentsCounter){

            $this->notification()->info(
                title: "La procédure ne peut être lancée : aucun enregistrement trouvé",
                description: "Pour les conditions que vous avez définies, aucun enregistrement n'a été trouvé dans la base de données!",
            );
        }

        return;

        JobToGeneratePrintableStudentsDataForThePrintViewComponent::dispatch(
            tenantId: tenant('id') ,
            notifiableId : auth('tenant')->user()->id,
            docTitle: 'liste apprenants',
            school_year_id: $this->activeYear->id,
            config: [
                "trashedConfig" => $this->trashedStatus,
                "leavesConfig" => $this->studentTypesActivesOrNotTargeted,
                "hasClasseConfig" => $this->studentsTypesWithOrWithoutClasses,
                "classe_id" => $this->classe_id,
                "filiar_id" => $this->filiar_id,
                "serial_id" => $this->serial_id,
                "promotion_id" => $this->promotion_id,
                "promotionInGroups" => $this->promotionInGroups,
                "gender" => $this->gender,
                "city" => $this->city,
                "department" => $this->department,
            ],

        );
    }



    public function render()
    {
        return view('livewire.tenants.students.students-prints-manager-component');
    }
}
