<?php

namespace App\Livewire\Tenants\Students;

use App\Jobs\JobToGeneratePrintableStudentsDataForThePrintViewComponent;
use App\Livewire\Tenants\ActionsTraits\StudentsActions;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\GeneratedDocument;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Student;
use App\Models\Subject;
use App\Services\PDFFactory;
use App\Services\StudentsServices\StudentPrintColumns;
use App\Services\StudentsServices\StudentPrintQuery;
use App\Services\StudentsServices\StudentPrintSessionConfig;
use App\Tools\BeninData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Portails des apprenants')]
class StudentsPortal extends Component
{
    use StudentsActions, WithPagination;


    public ?string $search = null;

    public $counter = 3;

    public int $perPage = 40;

    public ?string $city = null;

    public ?string $gender = null;

    public ?string $department = null;

    public ?string $status = null;

    public ?int $subject_id = null;

    public ?int $filiar_id = null;

    public ?int $serial_id = null;

    public ?int $promotion_id = null;

    public ?int $classe_id = null;


    
    public function mount(?string $status = null)
    {
        if($status) $this->status = $status;

        if(session()->has('students_status_selected')){

            $this->status = session('students_status_selected');
        }

        if(session()->has('students_classe_selected')){

            $this->classe_id = session('students_classe_selected');
        }

        if(session()->has('students_filiar_selected')){

            $this->filiar_id = session('students_filiar_selected');
        }

        if(session()->has('students_subject_selected')){

            $this->subject_id = session('students_subject_selected');
        }

        if(session()->has('students_promotion_selected')){

            $this->promotion_id = session('students_promotion_selected');
        }

        if(session()->has('students_gender_selected')){

            $this->gender = session('students_gender_selected');
        }

        if(session()->has('students_city_selected')){

            $this->city = session('students_city_selected');
        }

        if(session()->has('students_department_selected')){

            $this->department = session('students_department_selected');
        }

        if(session()->has('students_serial_selected')){

            $this->serial_id = session('students_serial_selected');
        }


    }

    public function clearFilters()
    {
        session()->forget(
            [
                'students_city_selected', 
                'students_department_selected', 
                'students_gender_selected', 
                'students_promotion_selected', 
                'students_classe_selected',
                'students_filiar_selected',
                'students_serial_selected', 
                'students_subject_selected',
                'students_status_selected',
            ]
        );


        $this->reset('search', 'gender', 'city', 'gender', 'department', 'classe_id', 'subject_id', 'promotion_id', 'filiar_id', 'serial_id', 'status');
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

    public function onReloadDashboard()
    {
        $this->counter = randomNumber();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingDepartment(): void
    {
        $this->resetPage();
    }

    public function updatedDepartment(?string $value): void
    {
        session()->put('students_department_selected', $value);
    }

    public function updatingCity(): void
    {
        $this->resetPage();
    }

    public function updatedCity(?string $value): void
    {
        session()->put('students_city_selected', $value);
    }

    public function updatingGender(): void
    {
        $this->resetPage();
    }

    public function updatedGender(?string $value): void
    {
        session()->put('students_gender_selected', $value);
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(?string $value): void
    {
        session()->put('students_status_selected', $value);
    }

    public function updatingClasseId(): void
    {
        $this->resetPage();
    }

    public function updatedClasseId(?string $value): void
    {
        session()->put('students_classe_selected', $value);
    }

    public function updatingSubjectId(): void
    {
        $this->resetPage();
    }

    public function updatedSubjectId(?string $value): void
    {
        session()->put('students_subject_selected', $value);
    }

    public function updatingPromotionId(): void
    {
        $this->resetPage();
    }

    public function updatedPromotionId(?string $value): void
    {
        session()->put('students_promotion_selected', $value);
    }
    
    public function updatingFiliarId(): void
    {
        $this->resetPage();
    }

    public function updatedFiliarId(?string $value): void
    {
        session()->put('students_filiar_selected', $value);
    }

    public function updatingSerialId(): void
    {
        $this->resetPage();
    }

    public function updatedSerialId(?string $value): void
    {
        session()->put('students_serial_selected', $value);
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



    #[Computed]
    public function hasPrintSessionConfig(): bool
    {
        return StudentPrintSessionConfig::hasActiveSelection();
    }

    public function printStudentsList()
    {
        if (! StudentPrintSessionConfig::hasActiveSelection()) {
            $this->notification()->warning(
                title: "Aucune configuration d'impression trouvée",
                description: "Configure d'abord les filtres et colonnes depuis la page d'impression.",
            );
            return;
        }

        $config = [
            ...StudentPrintSessionConfig::filterConfig(),
            'tableColumns' => StudentPrintSessionConfig::tableColumns(),
        ];

        $docTitle = StudentPrintQuery::resolveDocTitle(StudentPrintSessionConfig::filterConfig());

        JobToGeneratePrintableStudentsDataForThePrintViewComponent::dispatch(
            tenantId:       tenant('id'),
            notifiableId:   auth('tenant')->user()->id,
            docTitle:       $docTitle,
            school_year_id: $this->activeYear->id,
            config:         $config,
        );

        $this->notification()->success(
            title: 'Génération du document lancée',
        );
        $this->notification()->success(
            title: 'Génération du document lancée',
        );
    }


	public function resetFilters(): void
    {
        $this->reset(['search', 'gender', 'city', 'department', 'classe_id', 'promotion_id', 'filiar_id', 'serial_id']);
        
        $this->resetPage();
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
    public function students()
    {
        return $this->getStudentsData()->paginate($this->perPage);
    }
    
    #[Computed]
    public function activesStudentsCounter()
    {
        return  Student::whereStatus('active')->count();
    }
    
    #[Computed]
    public function allStudentsCounter()
    {
        return  $this->students->total();
    }


    public function render()
    {
        return view('livewire.tenants.Students.Students-portal');
    }


    public function trackDownload(int $documentId)
    {
        $doc = GeneratedDocument::where('id', $documentId)
            ->where('user_id', auth('tenant')->user()->id)
            ->first();

        if (! $doc) return abort(404, "Document introuvable ou déjà supprimé!");

        $doc?->recordDownload();

        $this->notification()->success(
            title: 'Téléchargement de la liste est en cours...',
        );

        return response()->download($doc->path, $doc->filename);
    }


    public function getStudentsData()
    {
        return Student::query()
        ->select('students.*')
        ->withTrashed()
        ->when($this->search, function (Builder $query) {
            $query->where('email', 'like', "%{$this->search}%");
            $query->orwhere('name', 'like', "%{$this->search}%");
            $query->orwhere('prenames', 'like', "%{$this->search}%");
            $query->orwhere('contacts', 'like', "%{$this->search}%");
            $query->orwhere('adresse', 'like', "%{$this->search}%");
            $query->orwhere('city', 'like', "%{$this->search}%");
            $query->orwhere('department', 'like', "%{$this->search}%");
            $query->orwhere('country', 'like', "%{$this->search}%");
            $query->orwhere('educMaster', 'like', "%{$this->search}%");
            $query->orwhere('gender', 'like', "%{$this->search}%");
            $query->orwhere('birth_date', 'like', "%{$this->search}%");
            $query->orwhere('birth_place', 'like', "%{$this->search}%");
            $query->orwhere('father_full_name', 'like', "%{$this->search}%");
            $query->orwhere('mother_full_name', 'like', "%{$this->search}%");
            $query->orwhere('matricule', 'like', "%{$this->search}%");
            $query->orwhere('status', 'like', "%{$this->search}%");
        })
        ->when($this->city, function (Builder $query) {
            $query->where('city', $this->city);
        })
        ->when($this->department, function (Builder $query) {
            $query->where('department', $this->department);
        })
        ->when($this->classe_id, function (Builder $query) {
            $query->whereHas('classes', fn($q) => 
                $q->where('is_active', true)->where('classe_id', $this->classe_id)->where('school_year_id', $this->activeYear->id)
            );
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
        ->when($this->gender, fn($q) => $q->whereIn('gender', [$this->gender, Str::lower($this->gender), Str::upper($this->gender)]))
        ->when($this->status, function($qst){

            if($this->status === 'actifs'){

                $qst->where('is_active', true);
            }
            elseif($this->status === 'desactives'){

                $qst->where('is_active', false);

            }
            elseif($this->status === 'de la corbeille'){

                $qst->whereNotNull('deleted_at');

            }


        })
        ->orderBy('students.name')
        ->orderBy('students.prenames');
        
    }
    
}
