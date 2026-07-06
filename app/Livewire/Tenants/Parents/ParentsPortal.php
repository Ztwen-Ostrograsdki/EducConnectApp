<?php

namespace App\Livewire\Tenants\Parents;

use App\Livewire\Tenants\ActionsTraits\TutorsActions;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use App\Models\Tutor;
use App\Tools\BeninData;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Title("Portail des parents et tuteurs des élèves")]
#[Layout('livewire.layouts.tenant-auth-layout')]
class ParentsPortal extends Component
{
    use WireUiActions, WithPagination, TutorsActions;

    public string $search = '';

    public string $city = '';

    public string $gender = '';

    public string $department = '';

    public ?string $status = null;

    public ?int $filiar_id = null;

    public ?int $serial_id = null;

    public ?int $promotion_id = null;

    public ?int $classe_id = null;

    public int $counterh = 10;

    public int $perPage = 10;


    
    public function mount(?string $status = null)
    {
        if($status) $this->status = $status;

        if(session()->has('parents_status_selected')){

            $this->status = session('parents_status_selected');
        }

        if(session()->has('parents_classe_selected')){

            $this->classe_id = session('parents_classe_selected');
        }

        if(session()->has('parents_filiar_selected')){

            $this->filiar_id = session('parents_filiar_selected');
        }

        if(session()->has('parents_promotion_selected')){

            $this->promotion_id = session('parents_promotion_selected');
        }

        if(session()->has('parents_gender_selected')){

            $this->gender = session('parents_gender_selected');
        }

        if(session()->has('parents_city_selected')){

            $this->city = session('parents_city_selected');
        }

        if(session()->has('parents_department_selected')){

            $this->department = session('parents_department_selected');
        }

        if(session()->has('parents_serial_selected')){

            $this->serial_id = session('parents_serial_selected');
        }


    }

    public function clearFilters()
    {
        session()->forget(
            [
                'parents_city_selected', 
                'parents_department_selected', 
                'parents_gender_selected', 
                'parents_promotion_selected', 
                'parents_classe_selected',
                'parents_filiar_selected',
                'parents_serial_selected', 
                'parents_subject_selected',
                'parents_status_selected',
            ]
        );


        $this->reset('search', 'gender', 'city', 'gender', 'department', 'classe_id', 'promotion_id', 'filiar_id', 'serial_id', 'status');
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
        $this->counterh = randomNumber();
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
        session()->put('parents_department_selected', $value);
    }

    public function updatingCity(): void
    {
        $this->resetPage();
    }

    public function updatedCity(?string $value): void
    {
        session()->put('parents_city_selected', $value);
    }

    public function updatingGender(): void
    {
        $this->resetPage();
    }

    public function updatedGender(?string $value): void
    {
        session()->put('parents_gender_selected', $value);
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(?string $value): void
    {
        session()->put('parents_status_selected', $value);
    }

    public function updatingClasseId(): void
    {
        $this->resetPage();
    }

    public function updatedClasseId(?string $value): void
    {
        session()->put('parents_classe_selected', $value);
    }

    public function updatingPromotionId(): void
    {
        $this->resetPage();
    }

    public function updatedPromotionId(?string $value): void
    {
        session()->put('parents_promotion_selected', $value);
    }
    
    public function updatingFiliarId(): void
    {
        $this->resetPage();
    }

    public function updatedFiliarId(?string $value): void
    {
        session()->put('parents_filiar_selected', $value);
    }

    public function updatingSerialId(): void
    {
        $this->resetPage();
    }

    public function updatedSerialId(?string $value): void
    {
        session()->put('parents_serial_selected', $value);
    }

    public function getParentsData()
    {
        return Tutor::query()
        ->select('tutors.*')
        ->join('users', 'users.id', '=', 'tutors.user_id')
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
            $qcl1->whereHas('myChildren', fn($qcl2) => 
                $qcl2->whereHas('student', fn($qcl3) => 
                    $qcl3->whereHas('classes', fn($qcl4) => 
                        $qcl4->where('classe_id', $this->classe_id)->where('is_active', true)->where('school_year_id', $this->activeYear->id)->whereNull('ended_at')
                    )
                )
            )
        )
        ->when($this->filiar_id, fn($qf1) => 
            $qf1->whereHas('myChildren', fn($qf2) => 
                $qf2->whereHas('student', fn($qf3) => 
                    $qf3->whereHas('classes', fn($qf4) => 
                            $qf4->whereHas('classe' , fn($qf5) => 
                                $qf5->where('filiar_id', $this->filiar_id)
                                    ->where('is_active', true)
                                    ->where('school_year_id', $this->activeYear->id)
                                    ->whereNull('ended_at')
                            )
                    )
                )
            )
        )
        ->when($this->serial_id, fn($qs1) => 
           $qs1->whereHas('myChildren', fn($qs2) => 
                $qs2->whereHas('student', fn($qs3) => 
                    $qs3->whereHas('classes', fn($qs4) => 
                            $qs4->whereHas('classe' , fn($qs5) => 
                                $qs5->where('serial_id', $this->serial_id)
                                    ->where('is_active', true)
                                    ->where('school_year_id', $this->activeYear->id)
                                    ->whereNull('ended_at')
                            )
                    )
                )
            )
        )
        ->when($this->promotion_id, fn($qp1) => 
            $qp1->whereHas('myChildren', fn($qp2) => 
                $qp2->whereHas('student', fn($qp3) => 
                    $qp3->whereHas('classes', fn($qp4) => 
                            $qp4->whereHas('classe' , fn($qp5) => 
                                $qp5->where('promotion_id', $this->promotion_id)
                                    ->where('is_active', true)
                                    ->where('school_year_id', $this->activeYear->id)
                                    ->whereNull('ended_at')
                            )
                    )
                )
            )
        )
        ->orderBy('users.name')
        ->orderBy('users.prenames');
    }



    #[Computed]
    public function tutors()
    {
        return $this->getParentsData()->paginate($this->perPage);
    }


    public function render()
    {
        return view('livewire.tenants.parents.parents-portal');
    }
}
