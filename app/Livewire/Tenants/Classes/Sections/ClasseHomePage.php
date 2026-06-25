<?php

namespace App\Livewire\Tenants\Classes\Sections;

use App\Events\DataUpdatedEvent;
use App\Models\Classe;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ClasseHomePage extends Component
{
    use WireUiActions;

    public string $classroom;

    public ?Classe $classe;

    public $counter = 0;


    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata()
    {
        $this->counter++;
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

        $classe->update(['is_locked' => true]);

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

        $classe->update(['is_locked' => false]);

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

        $classe->update(['is_active' => true]);

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

        $classe->update(['is_active' => false]);

        broadcast(new DataUpdatedEvent(tenant('id')));

        $this->notification()->success(
                title: 'Classe ' . $classe->name . 'fermée',
                description: "La classe {$classe->name} a été fermée avec succès.",
            );
    }



    public function render()
    {
        return view('livewire.tenants.classes.sections.classe-home-page');
    }
}
