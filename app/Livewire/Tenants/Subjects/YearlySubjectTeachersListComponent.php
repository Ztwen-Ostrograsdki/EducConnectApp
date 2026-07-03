<?php

namespace App\Livewire\Tenants\Subjects;

use App\Livewire\Tenants\ActionsTraits\TeachersActions;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\Subject;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class YearlySubjectTeachersListComponent extends Component
{
    use TeachersActions, WithPagination;

    public string $search = '';

    public string $gender = '';

    public ?string $status = null;

    public ?int $filiar_id = null;

    public ?int $serial_id = null;

    public ?int $promotion_id = null;

    public ?int $classe_id = null;

    public int $perPage = 10;

    public ?Subject $subject;


    
    public function mount()
    {
        if(session()->has('subject_teachers_status_selected')){

            $this->status = session('subject_teachers_status_selected');
        }

        if(session()->has('subject_teachers_classe_selected')){

            $this->classe_id = session('subject_teachers_classe_selected');
        }

        if(session()->has('subject_teachers_filiar_selected')){

            $this->filiar_id = session('subject_teachers_filiar_selected');
        }

        if(session()->has('subject_teachers_promotion_selected')){

            $this->promotion_id = session('subject_teachers_promotion_selected');
        }

        if(session()->has('subject_teachers_gender_selected')){

            $this->gender = session('subject_teachers_gender_selected');
        }

        if(session()->has('subject_teachers_serial_selected')){

            $this->serial_id = session('subject_teachers_serial_selected');
        }


    }

    public function resetFilters()
    {
        session()->forget(
            [
                'subject_teachers_gender_selected', 
                'subject_teachers_promotion_selected', 
                'subject_teachers_classe_selected',
                'subject_teachers_filiar_selected',
                'subject_teachers_serial_selected', 
                'subject_teachers_status_selected',
            ]
        );


        $this->reset('search', 'gender', 'classe_id', 'promotion_id', 'filiar_id', 'serial_id', 'status');
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
    public function promotions()
    {
        return Promotion::where('is_active', true)->orderBy('name', 'desc')->get();
    }

    #[Computed]
    public function teachers()
    {
        return $this->subject->getSubjectTeachersOfSchoolYear(

        )->paginate($this->perPage);
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
        session()->put('subject_teachers_gender_selected', $value);
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(?string $value): void
    {
        session()->put('subject_teachers_status_selected', $value);
    }

    public function updatingClasseId(): void
    {
        $this->resetPage();
    }

    public function updatedClasseId(?string $value): void
    {
        session()->put('subject_teachers_classe_selected', $value);
    }

    public function updatingPromotionId(): void
    {
        $this->resetPage();
    }

    public function updatedPromotionId(?string $value): void
    {
        session()->put('subject_teachers_promotion_selected', $value);
    }
    
    public function updatingFiliarId(): void
    {
        $this->resetPage();
    }

    public function updatedFiliarId(?string $value): void
    {
        session()->put('subject_teachers_filiar_selected', $value);
    }

    public function updatingSerialId(): void
    {
        $this->resetPage();
    }

    public function updatedSerialId(?string $value): void
    {
        session()->put('subject_teachers_serial_selected', $value);
    }



    public function render()
    {
        return view('livewire.tenants.subjects.yearly-subject-teachers-list-component');
    }
}
