<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Helpers\Support\TenantStorage;
use App\Models\Gallery;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;

trait GalleryActions
{
    use WireUiActions;

    public function hideGallery(string $uuid): void
    {
        $this->dispatch('swal', [
            'title'              => "Masquer cette image de la galerie ?",
            'text'               => "L'image ne sera plus visible publiquement.",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, masquer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToHideGallery',
            'onConfirmedParams'  => ['uuid' => $uuid],
        ]);
    }

    #[On('ConfirmToHideGallery')]
    public function onConfirmToHideGallery(string $uuid): void
    {
        $gallery = Gallery::firstWhere('uuid', $uuid);

        if (! $gallery) {
            $this->notification()->error(title: "Image introuvable");
            return;
        }

        try {
            $done = $gallery->update(['hidden' => true]);

            if ($done) {
                $this->notification()->success(
                    title: "Image masquée",
                    description: "L'image a été masquée avec succès.",
                );
                broadcast(new DataUpdatedEvent(tenant('id')));
            } else {
                $this->notification()->error(
                    title: "Échec",
                    description: "Impossible de masquer l'image.",
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "Erreur",
                description: cutter($th->getMessage(), 2000),
            );
        }
    }

    public function unhideGallery(string $uuid): void
    {
        $this->dispatch('swal', [
            'title'              => "Rendre visible cette image ?",
            'text'               => "L'image sera de nouveau visible publiquement.",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, rendre visible',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToUnhideGallery',
            'onConfirmedParams'  => ['uuid' => $uuid],
        ]);
    }

    #[On('ConfirmToUnhideGallery')]
    public function onConfirmToUnhideGallery(string $uuid): void
    {
        $gallery = Gallery::firstWhere('uuid', $uuid);

        if (! $gallery) {
            $this->notification()->error(title: "Image introuvable");
            return;
        }

        try {
            $done = $gallery->update(['hidden' => false]);

            if ($done) {
                $this->notification()->success(
                    title: "Image visible",
                    description: "L'image est de nouveau visible.",
                );
                broadcast(new DataUpdatedEvent(tenant('id')));
            } else {
                $this->notification()->error(
                    title: "Échec",
                    description: "Impossible de rendre l'image visible.",
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "Erreur",
                description: cutter($th->getMessage(), 2000),
            );
        }
    }

    public function deleteGallery(string $uuid): void
    {
        $this->dispatch('swal', [
            'title'              => "Supprimer cette image ?",
            'text'               => "Cette action est irréversible. L'image sera définitivement supprimée du stockage.",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#dc2626',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteGallery',
            'onConfirmedParams'  => ['uuid' => $uuid],
        ]);
    }

    #[On('ConfirmToDeleteGallery')]
    public function onConfirmToDeleteGallery(string $uuid): void
    {
        $gallery = Gallery::firstWhere('uuid', $uuid);

        if (! $gallery) {
            $this->notification()->error(title: "Image introuvable");
            return;
        }

        try {
            // Supprimer le fichier du storage
            if ($gallery->path) {
                try {
                    TenantStorage::delete($gallery->path);
                } catch (\Throwable $e) {
                    // On continue même si le fichier n'existe plus
                }
            }

            $done = $gallery->delete();

            if ($done) {
                $this->notification()->success(
                    title: "Image supprimée",
                    description: "L'image a été supprimée avec succès.",
                );
                broadcast(new DataUpdatedEvent(tenant('id')));
            } else {
                $this->notification()->error(
                    title: "Échec",
                    description: "Impossible de supprimer l'image.",
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "Erreur",
                description: cutter($th->getMessage(), 2000),
            );
        }
    }
}
