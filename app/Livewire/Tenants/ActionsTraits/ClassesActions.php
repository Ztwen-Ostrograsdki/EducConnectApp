<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
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


    public ?string $targetedClasseUuid = null;



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

        $school_year = SchoolYear::current()->first();

        if(!$school_year){

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Erreur processus',
                'description' => "Aucune année scolaire n'est active",
            ]);

            return [];

        } 

        $classes = Classe::where('school_year_id', $school_year->id)->withCount('students')->get();

        return [
            'classes'    => $classes->count(),
            'students'   => $classes->sum('students_count'),
            'teachers'   => TeacherYearlyAccess::where('school_year_id', $school_year->id)->where('status', 'active')->whereNull('suspended_at')->distinct('teacher_id')->count(),
            'promotions' => Classe::where('school_year_id', $school_year->id)
                               ->distinct('promotion_id')
                               ->count('promotion_id'),
        ];
    }


    public function lockClasse(?int $classeId = null)
    {
         $this->dispatch('swal', [
            'title'             => 'Verrouiller la classe ?',
            'text'              => "Les enseignants n'auront plus accès.",
            'icon'              => 'warning',
            'showCancelButton'  => true,
            'confirmButtonText' => 'Oui, verrouiller',
            'cancelButtonText'  => 'Annuler',
            'confirmButtonColor' => '#6366f1',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmClasseLock',
            'onConfirmedParams'  => ['classeId' => $classeId], // ← paramètres transmis
        ]);
    }

    #[On('ConfirmClasseLock')]
    public function onConfirmClasseLock(?int $classeId = null): void
    {
        $classe = Classe::findOrFail($classeId);

        $classe?->update(['is_locked' => true]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
                title: 'Classe ' . $classe->name . 'Verrouillée',
                description: "La classe {$classe->name} a été verrouillée avec succès.",
            );
    }


    public function unlockClasse(?int $classeId = null)
    {
         $this->dispatch('swal', [
            'title'             => 'Déverrouiller la classe ?',
            'text'              => 'La classe sera de nouveau accessible',
            'icon'              => 'warning',
            'showCancelButton'  => true,
            'confirmButtonText' => 'Oui, déverrouiller',
            'cancelButtonText'  => 'Annuler',
            'confirmButtonColor' => '#6366f1',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmClasseUnlock',
            'onConfirmedParams'  => ['classeId' => $classeId], // ← paramètres transmis
        ]);
    }

    #[On('ConfirmClasseUnlock')]
    public function onConfirmClasseLocked(?int $classeId = null): void
    {
        $classe = Classe::findOrFail($classeId);

        $classe?->update(['is_locked' => false]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
                title: 'Classe ' . $classe->name . 'déverrouillée',
                description: "La classe {$classe->name} a été déverrouillée avec succès.",
            );
    }


    public function activateClasse(?int $classeId = null)
    {
         $this->dispatch('swal', [
            'title'             => 'Activer la classe ?',
            'text'              => 'La classe sera de nouveau accessible',
            'icon'              => 'warning',
            'showCancelButton'  => true,
            'confirmButtonText' => 'Oui, Activer',
            'cancelButtonText'  => 'Annuler',
            'confirmButtonColor' => '#6366f1',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmClasseActivation',
            'onConfirmedParams'  => ['classeId' => $classeId], // ← paramètres transmis
        ]);
    }

    #[On('ConfirmClasseActivation')]
    public function onConfirmClasseActivation(?int $classeId = null): void
    {
        $classe = Classe::findOrFail($classeId);

        $classe?->update(['is_active' => true]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
                title: 'Classe ' . $classe->name . 'activée',
                description: "La classe {$classe->name} a été activée | réouverte avec succès.",
            );
    }


    public function closeClasse(?int $classeId = null)
    {
        $this->dispatch('swal', [
            'title'             => 'Fermer la classe ?',
            'text'              => 'La classe ne plus sera plus accessible au cours de cette année',
            'icon'              => 'warning',
            'showCancelButton'  => true,
            'confirmButtonText' => 'Oui, fermer',
            'cancelButtonText'  => 'Annuler',
            'confirmButtonColor' => '#6366f1',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToClasseClose',
            'onConfirmedParams'  => ['classeId' => $classeId], // ← paramètres transmis
        ]);
    }

    #[On('ConfirmToClasseClose')]
    public function onConfirmToClasseClose(?int $classeId = null): void
    {
        $classe = Classe::findOrFail($classeId);

        $classe?->update(['is_active' => false]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
                title: 'Classe ' . $classe->name . 'fermée',
                description: "La classe {$classe->name} a été fermée avec succès.",
            );
    }


	public function moveClasseToTrash(int $classeId): void
    {
        $this->dispatch('swal', [
            'title'             => 'Supprimer la classe ?',
            'text'              => 'La classe sera envoyée dans la corbeille et sera définitivement supprimée après 30 jours',
            'icon'              => 'warning',
            'showCancelButton'  => true,
            'confirmButtonText' => 'Oui, Supprimer',
            'cancelButtonText'  => 'Annuler',
            'confirmButtonColor' => '#6366f1',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToMoveClasseToTrash',
            'onConfirmedParams'  => ['classeId' => $classeId], // ← paramètres transmis
        ]);

    }

    #[On('ConfirmToMoveClasseToTrash')]
    public function onConfirmToMoveClasseToTrash(int $classeId): void
    {
       $classe = Classe::findOrFail($classeId);

        if($classe){

			$classe_name = $classe->name;

			$classe?->delete();

			$this->notification()->send([
				'icon'        => 'success',
				'title'       => 'Classe envoyée dans la corbeille',
				'description' => "Le classe " . $classe_name . " a été envoyée dans la corbeille et ne sera donc plus accessible sur la plateforme. Elle sera complètement supprimée après une durée de 30 jours",
			]);

            broadcast(new DataUpdatedEvent(tenant('id')));

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Classe introuvable',
                'description' => "La classe n'existe pas dans la base de données",
            ]);
            
        }
        
        
    }



	
}