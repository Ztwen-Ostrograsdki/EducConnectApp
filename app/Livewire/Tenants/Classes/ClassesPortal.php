<?php

namespace App\Livewire\Tenants\Classes;

use App\Livewire\Tenants\ActionsTraits\ClassesActions;
use App\Models\Classe;
use App\Services\ClassesServices\ClasseEffectifsService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.layouts.tenant-auth-layout')]
#[Title("Portail des classes")]
class ClassesPortal extends Component
{

    use ClassesActions;



    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPromotion()
    {
        $this->resetPage();
    }

    public function updatingFiliar()
    {
        $this->resetPage();
    }

    public function updatingSerial()
    {
        $this->resetPage();
    }



    #[Computed]
    public function classes()
    {
        $yearId = $this->activeYear?->id;

        return Classe::query()
            ->where('school_year_id', $yearId)
            ->with(['promotion', 'filiar', 'serial', 'principal', 'students'])
            ->withCount('students')
            ->when($this->search, fn($q) =>
                $q->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
                })
            )
            ->when($this->promotion, fn($q) => $q->where('promotion_id', $this->promotion))
            ->when($this->filiar,    fn($q) => $q->where('filiar_id', $this->filiar))
            ->when($this->serial,    fn($q) => $q->where('serial_id', $this->serial))
            ->orderBy('updated_at')
            ->paginate($this->perPage);
    }


    // ─── Render ───────────────────────────────────────────────────────
    public function render()
    {
        return view('livewire.tenants.classes.classes-portal');
    }
}