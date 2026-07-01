<?php

namespace App\Livewire\Tenants\Teachers;

use App\Livewire\Tenants\ActionsTraits\TeachersActions;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\GeneratedDocument;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\PDFFactory;
use App\Tools\BeninData;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title('Portails des enseignants')]
class TeachersPortal extends Component
{
    use TeachersActions;

    public string $search = '';

    public string $city = '';

    public string $gender = '';

    public string $department = '';

    public ?string $status = null;

    public ?int $subject_id = null;

    public ?int $filiar_id = null;

    public ?int $serial_id = null;

    public ?int $promotion_id = null;

    public ?int $classe_id = null;

    public int $perPage = 10;


    
    public function mount(?string $status = null)
    {
        if($status) $this->status = $status;

        if(session()->has('teachers_status_selected')){

            $this->status = session('teachers_status_selected');
        }

        if(session()->has('teachers_classe_selected')){

            $this->classe_id = session('teachers_classe_selected');
        }

        if(session()->has('teachers_filiar_selected')){

            $this->filiar_id = session('teachers_filiar_selected');
        }

        if(session()->has('teachers_subject_selected')){

            $this->subject_id = session('teachers_subject_selected');
        }

        if(session()->has('teachers_promotion_selected')){

            $this->promotion_id = session('teachers_promotion_selected');
        }

        if(session()->has('teachers_gender_selected')){

            $this->gender = session('teachers_gender_selected');
        }

        if(session()->has('teachers_city_selected')){

            $this->city = session('teachers_city_selected');
        }

        if(session()->has('teachers_department_selected')){

            $this->department = session('teachers_department_selected');
        }

        if(session()->has('teachers_serial_selected')){

            $this->serial_id = session('teachers_serial_selected');
        }


    }

    public function clearFilters()
    {
        session()->forget(
            [
                'teachers_city_selected', 
                'teachers_department_selected', 
                'teachers_gender_selected', 
                'teachers_promotion_selected', 
                'teachers_classe_selected',
                'teachers_filiar_selected',
                'teachers_serial_selected', 
                'teachers_subject_selected',
                'teachers_status_selected',
            ]
        );


        $this->reset('search', 'gender', 'city', 'gender', 'department', 'classe_id', 'subject_id', 'promotion_id', 'filiar_id', 'serial_id', 'status');
    }

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
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
        session()->put('teachers_department_selected', $value);
    }

    public function updatingCity(): void
    {
        $this->resetPage();
    }

    public function updatedCity(?string $value): void
    {
        session()->put('teachers_city_selected', $value);
    }

    public function updatingGender(): void
    {
        $this->resetPage();
    }

    public function updatedGender(?string $value): void
    {
        session()->put('teachers_gender_selected', $value);
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(?string $value): void
    {
        session()->put('teachers_status_selected', $value);
    }

    public function updatingClasseId(): void
    {
        $this->resetPage();
    }

    public function updatedClasseId(?string $value): void
    {
        session()->put('teachers_classe_selected', $value);
    }

    public function updatingSubjectId(): void
    {
        $this->resetPage();
    }

    public function updatedSubjectId(?string $value): void
    {
        session()->put('teachers_subject_selected', $value);
    }

    public function updatingPromotionId(): void
    {
        $this->resetPage();
    }

    public function updatedPromotionId(?string $value): void
    {
        session()->put('teachers_promotion_selected', $value);
    }
    
    public function updatingFiliarId(): void
    {
        $this->resetPage();
    }

    public function updatedFiliarId(?string $value): void
    {
        session()->put('teachers_filiar_selected', $value);
    }

    public function updatingSerialId(): void
    {
        $this->resetPage();
    }

    public function updatedSerialId(?string $value): void
    {
        session()->put('teachers_serial_selected', $value);
    }




    #[On("TeachersPDFCompletedSuccessfullyLiveEvent")]
    public function pdfUpdated()
    {
        $this->onReloadDashboard();
    }

    public function printTeachersList()
    {
        $teachers = $this->getTeachersData()->get();

        $printed_at  = now()->isoFormat('dddd D MMMM YYYY [à] HH:mm');

        $pdf_title = "Liste des enseignants";

        $viewData = [
            'teachers' => $teachers,
            'printed_at' => $printed_at,
            'allTeachers' => $teachers->count(),
            'totalActifs'      => $teachers->where('status', 'active')->count(),
            'pdf_title' => $pdf_title,
            'target'          => 'teachers',
            'eventName'       => 'TeachersPDFCompletedSuccessfullyLiveEvent',
        ];

        PDFFactory::dispatch(
            view:          'livewire.tenants.teachers.printable-list-component',
            data:           $viewData,
            filename:      'liste-enseignants',
            category:      'teachers',
            overrides:    ['landscape' => true],
            documentType:  'teacher_list',
            tenantId:      tenant('id'),
            notifiableId: auth('tenant')->user()->id
        );

        $this->notification()->success(
            title: 'Génération du document lancée',
        );
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


    public function getTeachersData()
    {
        return Teacher::query()
        ->select('teachers.*')
        ->join('users', 'users.id', '=', 'teachers.user_id')
        ->with(['user'])
        ->withTrashed()
        ->when($this->search, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('email', 'like', "%{$this->search}%");
                $query->orwhere('name', 'like', "%{$this->search}%");
                $query->orwhere('prenames', 'like', "%{$this->search}%");
                $query->orwhere('contacts', 'like', "%{$this->search}%");
                $query->orwhere('adresse', 'like', "%{$this->search}%");
                $query->orwhere('city', 'like', "%{$this->search}%");
                $query->orwhere('department', 'like', "%{$this->search}%");
                $query->orwhere('country', 'like', "%{$this->search}%");
                $query->orwhere('gender', 'like', "%{$this->search}%");
                $query->orwhere('birth_date', 'like', "%{$this->search}%");
                $query->orwhere('birth_place', 'like', "%{$this->search}%");
                $query->orwhere('job_name', 'like', "%{$this->search}%");
                $query->orwhere('status', 'like', "%{$this->search}%");
            })
            ->where('identifiant', 'like', "%{$this->search}%");
        })
        ->when($this->city, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('city', $this->city);
            });
        })
        ->when($this->department, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('department', $this->department);
            });
        })
        ->when($this->gender, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('gender', $this->gender);
            });
        })
        ->when($this->classe_id, fn($qcl1) => 
            $qcl1->whereHas('classeSubjects', fn($qcl2) => 
                $qcl2->where('classe_id', $this->classe_id)->where('is_active', true)->where('school_year_id', $this->activeYear->id)->whereNull('ended_at')
            )
        )
        ->when($this->filiar_id, fn($qcl1) => 
            $qcl1->whereHas('classeSubjects', fn($qcl2) => 
                $qcl2->where('is_active', true)
                     ->where('school_year_id', $this->activeYear->id)
                     ->whereNull('ended_at')
                     ->whereHas('classe', fn($q) => 
                        $q->where('filiar_id', $this->filiar_id)
                          ->where('is_active', true)
                          ->where('school_year_id', $this->activeYear->id)
                     )
            )
        )
        ->when($this->serial_id, fn($qcl1) => 
            $qcl1->whereHas('classeSubjects', fn($qcl2) => 
                $qcl2->where('is_active', true)
                     ->where('school_year_id', $this->activeYear->id)
                     ->whereNull('ended_at')
                     ->whereHas('classe', fn($q) => 
                        $q->where('serial_id', $this->serial_id)
                          ->where('is_active', true)
                          ->where('school_year_id', $this->activeYear->id)
                     )
            )
        )
        ->when($this->promotion_id, fn($qcl1) => 
            $qcl1->whereHas('classeSubjects', fn($qcl2) => 
                $qcl2->where('is_active', true)
                     ->where('school_year_id', $this->activeYear->id)
                     ->whereNull('ended_at')
                     ->whereHas('classe', fn($q) => 
                        $q->where('promotion_id', $this->promotion_id)
                          ->where('is_active', true)
                          ->where('school_year_id', $this->activeYear->id)
                     )
            )
        )
        ->when($this->subject_id, fn($qcl1) => 
            $qcl1->whereHas('yearlySubjects', fn($qcl2) => 
                $qcl2->where('subject_id', $this->subject_id)->where('is_active', true)->where('school_year_id', $this->activeYear->id)->whereNull('ended_at')
            )
        )
        
        ->orderBy('users.name')
        ->orderBy('users.prenames');
        
    }



    public function render()
    {
        $allTeachersCounter = Teacher::all()->count();

        $genders = config('app.genders');

        $activesTeachersCounter = Teacher::whereStatus('active')->count();

        $teachers = $this->getTeachersData()->paginate($this->perPage);

        return view('livewire.tenants.teachers.teachers-portal', compact('teachers', 'allTeachersCounter', 'activesTeachersCounter', 'genders'));
    }


}
