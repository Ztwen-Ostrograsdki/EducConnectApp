<?php

namespace App\Livewire\Tenants\Promotions;

use App\Livewire\Tenants\ActionsTraits\PromotionActions;
use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\Serial;
use App\Models\Student;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Portail des promotions")]
class PromotionsPortal extends Component
{
    use WithPagination, WireUiActions, PromotionActions;

    public ?string $search = null;

    public ?int $filiar_id = null;

    public ?int $serial_id = null;

    public int $perPage = 10;

    public int $counterh = 0;

    public string $system = "both";

    public array $systems = [
        'new_system' => "Les nouveaux métiers",
        'default_system' => "Les filières classiques",
        'both' => "Tous les systèmes",

    ];


    public function mount()
    {
        if(session()->has('promotions_selected_system')){

            $this->system = session('promotions_selected_system');
        }
    }

    public function updatedSystem(?string $value)
    {
        session()->put('promotions_selected_system', $this->system);
    }



    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counterh++;
    }

    #[Computed]
    public function serials()
    {
        return Serial::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function filiars()
    {
        return Filiar::where('is_active', true)->orderBy('name')->get();
    }

    #[Computed]
    public function classes()
    {
        return Classe::where('is_active', true)->where('school_year_id', $this->activeYear->id)->whereNotNull('promotion_id')->count();
    }
    
    #[Computed]
    public function promotions()
    {
        return Promotion::whereNotNull('name')
        ->when($this->search, fn($q) =>
            $q->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('code', 'like', '%'.$this->search.'%')
            ->orWhere('slug', 'like', '%'.$this->search.'%')
        )
        ->when($this->filiar_id, fn($qf) =>
            $qf->where('filiar_id', $this->filiar_id)
        )
        ->when($this->serial_id, fn($qs) =>
            $qs->where('serial_id', $this->serial_id)
        )
        ->when($this->system, function ($qs) {
            match ($this->system) {
                'new_system' => $qs->whereHas('classes', fn ($q) =>
                    $q->where('is_new_system', true)
                )->orWhere('is_new_system', true), 
                'default_system' => $qs->whereHas('classes', fn ($q) =>
                    $q->where('is_new_system', false)
                )->orWhere('is_new_system', false),
                'both' => $qs->where('is_new_system', true)->orWhere('is_new_system', false),
                default => $qs->where('is_new_system', true)->orWhere('is_new_system', false),
            };
        })
        ->orderBy('name')->paginate($this->perPage);
    }
    
    #[Computed]
    public function students()
    {
        return Student::where('is_active', true)->whereHas('yearlyClasseStudents', fn($q) => 
                                $q->whereNotNull('classe_id')
                                  ->where('school_year_id', $this->activeYear->id)
                                  ->where('is_active', true)
                                  ->whereNull('ended_at')
                            )->count();
    }


    public function render()
    {
        return view('livewire.tenants.promotions.promotions-portal');
    }
}
