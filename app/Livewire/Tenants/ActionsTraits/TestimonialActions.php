<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Models\Testimonial;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;

trait TestimonialActions
{
    use WireUiActions;

    public function hideTestimonial(string $uuid): void
    {
        $this->dispatch('swal', [
            'title'              => "Masquer ce témoignage ?",
            'text'               => "Le témoignage ne sera plus visible publiquement.",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, masquer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToHideTestimonial',
            'onConfirmedParams'  => ['uuid' => $uuid],
        ]);
    }

    #[On('ConfirmToHideTestimonial')]
    public function onConfirmToHideTestimonial(string $uuid): void
    {
        $testimonial = Testimonial::firstWhere('uuid', $uuid);

        if (! $testimonial) {
            $this->notification()->error(title: "Témoignage introuvable");
            return;
        }

        try {
            $done = $testimonial->update(['hidden' => true]);

            if ($done) {
                $this->notification()->success(
                    title: "Témoignage masqué",
                    description: "Le témoignage a été masqué avec succès.",
                );
                broadcast(new DataUpdatedEvent(tenant('id')));
            } else {
                $this->notification()->error(
                    title: "Échec",
                    description: "Impossible de masquer le témoignage.",
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "Erreur",
                description: cutter($th->getMessage(), 2000),
            );
        }
    }

    public function unhideTestimonial(string $uuid): void
    {
        $this->dispatch('swal', [
            'title'              => "Rendre visible ce témoignage ?",
            'text'               => "Le témoignage sera de nouveau visible publiquement.",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, rendre visible',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToUnhideTestimonial',
            'onConfirmedParams'  => ['uuid' => $uuid],
        ]);
    }

    #[On('ConfirmToUnhideTestimonial')]
    public function onConfirmToUnhideTestimonial(string $uuid): void
    {
        $testimonial = Testimonial::firstWhere('uuid', $uuid);

        if (! $testimonial) {
            $this->notification()->error(title: "Témoignage introuvable");
            return;
        }

        try {
            $done = $testimonial->update(['hidden' => false]);

            if ($done) {
                $this->notification()->success(
                    title: "Témoignage visible",
                    description: "Le témoignage est de nouveau visible.",
                );
                broadcast(new DataUpdatedEvent(tenant('id')));
            } else {
                $this->notification()->error(
                    title: "Échec",
                    description: "Impossible de rendre le témoignage visible.",
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: "Erreur",
                description: cutter($th->getMessage(), 2000),
            );
        }
    }

    public function deleteTestimonial(string $uuid): void
    {
        $this->dispatch('swal', [
            'title'              => "Supprimer ce témoignage ?",
            'text'               => "Cette action est irréversible.",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#dc2626',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteTestimonial',
            'onConfirmedParams'  => ['uuid' => $uuid],
        ]);
    }

    #[On('ConfirmToDeleteTestimonial')]
    public function onConfirmToDeleteTestimonial(string $uuid): void
    {
        $testimonial = Testimonial::firstWhere('uuid', $uuid);

        if (! $testimonial) {
            $this->notification()->error(title: "Témoignage introuvable");
            return;
        }

        try {
            $done = $testimonial->delete();

            if ($done) {
                $this->notification()->success(
                    title: "Témoignage supprimé",
                    description: "Le témoignage a été supprimé avec succès.",
                );
                broadcast(new DataUpdatedEvent(tenant('id')));
            } else {
                $this->notification()->error(
                    title: "Échec",
                    description: "Impossible de supprimer le témoignage.",
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
