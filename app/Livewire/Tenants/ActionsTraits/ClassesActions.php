<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Models\Classe;
use App\Models\Filiar;
use App\Models\Promotion;
use App\Models\SchoolYear;
use App\Models\Serial;
use App\Models\TeacherYearlyAccess;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;


trait ClassesActions{


	use WithPagination, WireUiActions;

    public string $search      = '';
    public string $promotion   = '';
    public string $filiar      = '';
    public string $serial      = '';
    public string $level       = '';
    public int    $perPage     = 9;


	public $showConfirmDeleteClasse = false;

	public $showConfirmLockClasse = false;

	public $showConfirmCloseClasse = false;

    public ?string $targetedClasseUuid = null;


	public function closeModal()
    {
        $this->reset('showConfirmDeleteClasse', 'showConfirmLockClasse', 'showConfirmCloseClasse');
    }

    // Reset pagination quand un filtre change
    public function updatedSearch(): void    { $this->resetPage(); }
    public function updatedPromotion(): void { $this->resetPage(); }
    public function updatedFiliar(): void    { $this->resetPage(); }
    public function updatedSerial(): void    { $this->resetPage(); }
    public function updatedLevel(): void     { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->reset(['search', 'promotion', 'filiar', 'serial', 'level']);

        $this->resetPage();
    }

    #[On('DataUpdatedEventLiveEvent')]
    public function reloadData(): void
    {
        $this->resetPage();
    }

    // ─── Computed ─────────────────────────────────────────────────────

    #[Computed]
    public function activeYear(): ?SchoolYear
    {
        return SchoolYear::current()->first();
    }

    #[Computed]
    public function promotions()
    {
        return Promotion::where('is_active', true)->orderBy('order')->get(['id', 'name']);
    }

    #[Computed]
    public function filiars()
    {
        return Filiar::where('is_active', true)->orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function serials()
    {
        return Serial::where('is_active', true)->orderBy('name')->get(['id', 'name']);
    }

    #[Computed]
    public function stats(): array
    {

        $yearId = $this->activeYear?->id;

        $classes = Classe::where('school_year_id', $yearId)->withCount('students')->get();

        return [
            'classes'    => $classes->count(),
            'students'   => $classes->sum('students_count'),
            'teachers'   => TeacherYearlyAccess::where('school_year_id', $yearId)->where('status', 'active')->whereNull('suspended_at')->distinct('teacher_id')->count(),
            'promotions' => Classe::where('school_year_id', $yearId)
                               ->distinct('promotion_id')
                               ->count('promotion_id'),
        ];
    }


	public function moveClasseToTrash(string $classeUuid): void
    {
        $this->showConfirmDeleteClasse = true;

        $this->targetedClasseUuid = $classeUuid;

    }

    public function ConfirmToMoveClasseToTrash(): void
    {
       $classe = Classe::whereUuid($this->targetedClasseUuid)->firstOrFail();

        if($classe){

			$classe_name = $classe->name;

            $domain = request()->getSchemeAndHttpHost();

			// $classe->delete();

			$this->notification()->send([
				'icon'        => 'success',
				'title'       => 'Classe envoyée dans la corbeille',
				'description' => "Le classe " . $classe_name . " a été envoyée dans la corbeille et ne sera donc plus accessible sur la plateforme. Elle sera complètement supprimée après une durée de 30 jours",
			]);

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'utilisateur introuvable',
                'description' => "L'utilisateur n'existe pas dans la base de données",
            ]);
            
        }
        $this->closeModal();
        
    }



	
}