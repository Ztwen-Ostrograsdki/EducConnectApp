<?php

namespace App\Livewire\Tenants\Promotions;

use App\Livewire\Tenants\ActionsTraits\TeachersActions;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Profil de promotion")]
class PromotionProfil extends Component
{
    use TeachersActions, WithPagination;

    public string $search = '';

    public string $gender = '';

    public ?string $status = null;

    public ?int $filiar_id = null;

    public ?int $serial_id = null;

    public int $perPage = 10;

    public int $counterh = 0;

    public $counter = 1;

    public Promotion $promotion;

    public string $promotion_slug;

    public string $promotion_name = 'Nom de la promotion';

    public ?string $school_year_selected;




    public function mount(string $promotion_slug)
    {
        if(!$promotion_slug) return abort(404);

        $this->promotion_slug  = $promotion_slug;

        $promotion = Promotion::withTrashed()->whereSlug($promotion_slug)?->first();

        if(!$promotion) return abort(404);

        $this->promotion       = $promotion;

        $this->promotion_name       = $promotion->name;


        if(session()->has('promotion_teachers_status_selected')){

            $this->status = session('promotion_teachers_status_selected');
        }

        if(session()->has('promotion_teachers_filiar_selected')){

            $this->filiar_id = session('promotion_teachers_filiar_selected');
        }


        if(session()->has('promotion_teachers_gender_selected')){

            $this->gender = session('promotion_teachers_gender_selected');
        }

        if(session()->has('promotion_teachers_serial_selected')){

            $this->serial_id = session('promotion_teachers_serial_selected');
        }
    }

    
    public function resetFilters()
    {
        session()->forget(
            [
                'promotion_teachers_gender_selected', 
                'promotion_teachers_filiar_selected',
                'promotion_teachers_serial_selected', 
                'promotion_teachers_status_selected',
            ]
        );


        $this->reset('search', 'gender', 'filiar_id', 'serial_id', 'status');
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


    public function onReloadDashboard()
    {
        $this->counter = randomNumber();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingGender(): void
    {
        $this->resetPage();
    }

    public function updatedGender(?string $value): void
    {
        session()->put('promotion_teachers_gender_selected', $value);
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(?string $value): void
    {
        session()->put('promotion_teachers_status_selected', $value);
    }

    public function updatingFiliarId(): void
    {
        $this->resetPage();
    }

    public function updatedFiliarId(?string $value): void
    {
        session()->put('promotion_teachers_filiar_selected', $value);
    }

    public function updatingSerialId(): void
    {
        $this->resetPage();
    }

    public function updatedSerialId(?string $value): void
    {
        session()->put('promotion_teachers_serial_selected', $value);
    }

    #[Computed]
    public function teachers()
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
        ->whereHas('classeSubjects', fn($qcl2) => 
            $qcl2->where('is_active', true)
                    ->where('school_year_id', $this->activeYear->id)
                    ->whereNull('ended_at')
                    ->whereHas('classe', fn($q) => 
                    $q->where('promotion_id', $this->promotion->id)
                        ->where('is_active', true)
                        ->where('school_year_id', $this->activeYear->id)
                    )
        )
        ->when($this->gender, function (Builder $query) {
            $query->whereHas('user', function ($query) {
                $query->where('gender', $this->gender);
            });
        })
        
       
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
        ->orderBy('users.name')
        ->orderBy('users.prenames')
        ->paginate(30);
    }


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counterh++;
        unset($this->teachers);
    }



    #[On('yearChanged')]
    public function onYearChanged(string $schoolYear)
    {
        $this->school_year_selected = $schoolYear;
    }

    public function render()
    {

        return view('livewire.tenants.promotions.promotion-profil');
    }
}
