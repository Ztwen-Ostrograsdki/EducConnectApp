<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Events\DataUpdatedEvent;
use App\Models\TimePlan;
use App\Models\TimePlanSlot;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;

trait TimePlanActions
{
    use WireUiActions;

    public $counter = 0;

    #[On('DataUpdatedEventLiveEvent')]
    public function reloaddata(): void
    {
        $this->counter++;
        $this->refreshPlansData();
    }

    /**
     * Invalide les computed liés aux plans côté composant hôte.
     * N’accède jamais à currentPlan en lecture métier.
     */
    protected function refreshPlansData(): void
    {
        unset($this->plans);

        if (property_exists($this, 'timePlanId') || isset($this->timePlanId)) {
            // Invalide les computed du dashboard s’ils existent
            try {
                unset($this->currentPlan, $this->assignments);
            } catch (\Throwable) {
                // silencieux : le trait peut être utilisé hors dashboard
            }
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  SUPPRESSION D'UN EMPLOI DU TEMPS
    // ─────────────────────────────────────────────────────────────

    public function deletePlan(int $planId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Supprimer cet emploi du temps ?',
            'text'               => 'Cette action supprimera définitivement l’emploi du temps et tous ses créneaux. Cette opération est irréversible.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeletePlan',
            'onConfirmedParams'  => ['planId' => $planId],
        ]);
    }

    #[On('ConfirmToDeletePlan')]
    public function onConfirmToDeletePlan(int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        try {
            $plan->slots()->delete();
            $done = $plan->delete();

            if ($done) {
                if (property_exists($this, 'timePlanId') && $this->timePlanId === $planId) {
                    $this->timePlanId = null;
                    session()->forget('current_time_plan');
                }

                $this->notification()->success(
                    title: 'Emploi du temps supprimé',
                    description: "L'emploi du temps a été supprimé avec succès.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
                $this->refreshPlansData();
            } else {
                $this->notification()->error(
                    title: 'Suppression échouée',
                    description: 'Une erreur est survenue, veuillez réessayer.',
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Suppression échouée',
                description: 'Une erreur est survenue : ' . cutter($th->getMessage(), 200),
            );
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  SUPPRESSION D'UN CRÉNEAU
    // ─────────────────────────────────────────────────────────────

    public function deleteSlot(int $slotId, int $planId): void
    {
        $this->dispatch('swal', [
            'title'              => 'Supprimer ce créneau ?',
            'text'               => 'Cette action retirera définitivement ce créneau de l’emploi du temps.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteSlot',
            'onConfirmedParams'  => ['slotId' => $slotId, 'planId' => $planId],
        ]);
    }

    #[On('ConfirmToDeleteSlot')]
    public function onConfirmToDeleteSlot(int $slotId, int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        if (!$plan->isEditable()) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Cet emploi du temps n’est pas modifiable.',
            );
            return;
        }

        $slot = TimePlanSlot::query()
            ->where('time_plan_id', $plan->id)
            ->find($slotId);

        if (!$slot) {
            $this->notification()->error(title: 'Créneau introuvable');
            return;
        }

        try {
            $done = $slot->delete();

            if ($done) {
                $this->notification()->success(
                    title: 'Créneau supprimé',
                    description: 'Le créneau a été supprimé avec succès.',
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
                $this->refreshPlansData();
            } else {
                $this->notification()->error(
                    title: 'Suppression échouée',
                    description: 'Une erreur est survenue, veuillez réessayer.',
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Suppression échouée',
                description: 'Une erreur est survenue : ' . cutter($th->getMessage(), 200),
            );
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  PUBLICATION
    // ─────────────────────────────────────────────────────────────

    public function publishPlan(int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        if ($plan->status === 'archived') {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Un emploi du temps archivé ne peut pas être publié.',
            );
            return;
        }

        if (!$plan->slots()->exists()) {
            $this->notification()->error(
                title: 'Emploi du temps vide',
                description: 'Ajoutez au moins un créneau avant de publier.',
            );
            return;
        }

        $this->dispatch('swal', [
            'title'              => 'Publier cet emploi du temps ?',
            'text'               => 'Une fois publié, l’emploi du temps sera visible par les enseignants et les élèves concernés.',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, publier',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#06b6d4',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToPublishPlan',
            'onConfirmedParams'  => ['planId' => $planId],
        ]);
    }

    #[On('ConfirmToPublishPlan')]
    public function onConfirmToPublishPlan(int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        try {
            $done = $plan->update([
                'status'       => 'published',
                'published_at' => now(),
            ]);

            if ($done) {
                $this->notification()->success(
                    title: 'Emploi du temps publié',
                    description: "L'emploi du temps a été publié avec succès.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
                $this->refreshPlansData();
            } else {
                $this->notification()->error(
                    title: 'Publication échouée',
                    description: 'Une erreur est survenue, veuillez réessayer.',
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Publication échouée',
                description: 'Une erreur est survenue : ' . cutter($th->getMessage(), 200),
            );
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  ARCHIVAGE
    // ─────────────────────────────────────────────────────────────

    public function archivePlan(int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        $this->dispatch('swal', [
            'title'              => 'Archiver cet emploi du temps ?',
            'text'               => 'L’emploi du temps ne sera plus modifiable. Vous pourrez le désarchiver plus tard.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, archiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f59e0b',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToArchivePlan',
            'onConfirmedParams'  => ['planId' => $planId],
        ]);
    }

    #[On('ConfirmToArchivePlan')]
    public function onConfirmToArchivePlan(int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        try {
            $done = $plan->update(['status' => 'archived']);

            if ($done) {
                $this->notification()->success(
                    title: 'Emploi du temps archivé',
                    description: "L'emploi du temps a été archivé.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
                $this->refreshPlansData();
            } else {
                $this->notification()->error(
                    title: 'Archivage échoué',
                    description: 'Une erreur est survenue, veuillez réessayer.',
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Archivage échoué',
                description: 'Une erreur est survenue : ' . cutter($th->getMessage(), 200),
            );
        }
    }

    // ─────────────────────────────────────────────────────────────
    //  DÉSARCHIVAGE
    // ─────────────────────────────────────────────────────────────

    public function unArchivePlan(int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        $this->dispatch('swal', [
            'title'              => 'Désarchiver cet emploi du temps ?',
            'text'               => 'L’emploi du temps redeviendra modifiable (statut brouillon).',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, désarchiver',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#06b6d4',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToUnArchivePlan',
            'onConfirmedParams'  => ['planId' => $planId],
        ]);
    }

    #[On('ConfirmToUnArchivePlan')]
    public function onConfirmToUnArchivePlan(int $planId): void
    {
        $plan = TimePlan::query()->find($planId);

        if (!$plan) {
            $this->notification()->error(title: 'Emploi du temps introuvable');
            return;
        }

        try {
            $done = $plan->update([
                'status'       => 'draft',
                'published_at' => null,
            ]);

            if ($done) {
                $this->notification()->success(
                    title: 'Emploi du temps désarchivé',
                    description: "L'emploi du temps est de nouveau modifiable.",
                );

                broadcast(new DataUpdatedEvent(tenant('id')));
                $this->refreshPlansData();
            } else {
                $this->notification()->error(
                    title: 'Désarchivage échoué',
                    description: 'Une erreur est survenue, veuillez réessayer.',
                );
            }
        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Désarchivage échoué',
                description: 'Une erreur est survenue : ' . cutter($th->getMessage(), 200),
            );
        }
    }
}
