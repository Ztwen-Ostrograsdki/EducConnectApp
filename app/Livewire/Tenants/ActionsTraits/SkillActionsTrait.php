<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Models\Skills;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\On;

/**
 * Actions métier communes sur Skills (création, édition, suppression,
 * activation/désactivation, actions de masse).
 *
 * Prérequis sur le composant hôte :
 * - WireUiActions (pour $this->notification())
 * - WithPagination si la liste est paginée
 * - propriété / computed `skills` (unset pour rafraîchir)
 * - optionnel : propriété $selected = [] pour les actions de masse
 *
 * Surcharges utiles :
 * - findSkillForManage()   → règles d’accès
 * - skillDeleteConfirmedEvent()
 * - canManageAllSkills()
 */
/**
 * @property-read LengthAwarePaginator $skills
 */
trait SkillActionsTrait
{
    /** @var array<int> IDs sélectionnés pour actions de masse */
    // public array $selected = []; // à déclarer dans le composant hôte si besoin

    protected function skillDeleteConfirmedEvent(): string
    {
        return 'ConfirmToDeleteSkill';
    }

    protected function skillBulkDeleteConfirmedEvent(): string
    {
        return 'ConfirmToBulkDeleteSkills';
    }

    /**
     * Le composant hôte est-il en mode « gestion globale » (directeur) ?
     */
    protected function canManageAllSkills(): bool
    {
        return true; // côté directeur par défaut
    }

    /**
     * Compétence accessible en gestion.
     */
    protected function findSkillForManage(int $skillId): ?Skills
    {
        return Skills::query()->where('id', $skillId)->first();
    }

    protected function refreshSkillsList(): void
    {
        unset($this->skills);
        if (property_exists($this, 'selected')) {
            $this->selected = [];
        }
    }

    protected function adjustPaginationAfterDelete(): void
    {
        if (! method_exists($this, 'getPage') && ! property_exists($this, 'paginators')) {
            return;
        }

        try {
            if ($this->skills->currentPage() > 1 && $this->skills->isEmpty()) {
                $this->setPage($this->skills->currentPage() - 1);
                $this->refreshSkillsList();
            }
        } catch (\Throwable) {
            //
        }
    }

    // ─── Suppression individuelle ─────────────────────────────────────

    public function confirmDeleteSkill(int $skillId): void
    {
        $skill = $this->findSkillForManage($skillId);

        if (! $skill) {
            $this->notification()->error(
                title: 'Suppression impossible',
                description: 'Compétence introuvable ou accès refusé.',
            );

            return;
        }

        $this->dispatch('swal', [
            'title'              => 'Supprimer cette compétence ?',
            'description'        => 'Cette action est irréversible. Les liaisons avec les modules seront également supprimées.',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#06b6d4',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => $this->skillDeleteConfirmedEvent(),
            'onConfirmedParams'  => ['skillId' => $skillId],
        ]);
    }

    #[On('ConfirmToDeleteSkill')]
    public function deleteSkill(int $skillId): void
    {
        $skill = $this->findSkillForManage($skillId);

        if (! $skill) {
            $this->notification()->error(
                title: 'Suppression impossible',
                description: 'Compétence introuvable ou accès refusé.',
            );

            return;
        }

        $skill->delete();

        $this->refreshSkillsList();
        $this->adjustPaginationAfterDelete();

        $this->notification()->success(title: 'Compétence supprimée avec succès.');
    }

    // ─── Toggle is_active ─────────────────────────────────────────────

    public function toggleSkillActive(int $skillId): void
    {
        $skill = $this->findSkillForManage($skillId);

        if (! $skill) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Compétence introuvable ou accès refusé.',
            );

            return;
        }

        $skill->update([
            'is_active' => ! (bool) $skill->is_active,
        ]);

        $this->refreshSkillsList();

        $active = (bool) $skill->fresh()->is_active;

        $this->notification()->success(
            title: $active ? 'Compétence activée' : 'Compétence désactivée',
            description: $active
                ? 'Cette compétence est maintenant active.'
                : 'Cette compétence est maintenant inactive.',
        );
    }

    // ─── Actions de masse ─────────────────────────────────────────────

    /**
     * @return \Illuminate\Support\Collection<int, Skills>
     */
    protected function selectedSkills()
    {
        $ids = property_exists($this, 'selected') ? array_filter($this->selected ?? []) : [];

        if (empty($ids)) {
            return collect();
        }

        return Skills::query()->whereIn('id', $ids)->get();
    }

    public function bulkActivateSkills(): void
    {
        $items = $this->selectedSkills();

        if ($items->isEmpty()) {
            $this->notification()->warning(title: 'Aucune compétence sélectionnée.');

            return;
        }

        Skills::query()->whereIn('id', $items->pluck('id'))->update(['is_active' => true]);
        $this->refreshSkillsList();

        $this->notification()->success(
            title: 'Activation effectuée',
            description: $items->count() . ' compétence(s) activée(s).',
        );
    }

    public function bulkDeactivateSkills(): void
    {
        $items = $this->selectedSkills();

        if ($items->isEmpty()) {
            $this->notification()->warning(title: 'Aucune compétence sélectionnée.');

            return;
        }

        Skills::query()->whereIn('id', $items->pluck('id'))->update(['is_active' => false]);
        $this->refreshSkillsList();

        $this->notification()->success(
            title: 'Désactivation effectuée',
            description: $items->count() . ' compétence(s) désactivée(s).',
        );
    }

    public function confirmBulkDeleteSkills(): void
    {
        $items = $this->selectedSkills();

        if ($items->isEmpty()) {
            $this->notification()->warning(title: 'Aucune compétence sélectionnée.');

            return;
        }

        $this->dispatch('swal', [
            'title'              => 'Supprimer ' . $items->count() . ' compétence(s) ?',
            'description'        => 'Cette action est irréversible. Les liaisons avec les modules seront également supprimées.',
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, tout supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#ef4444',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => $this->skillBulkDeleteConfirmedEvent(),
        ]);
    }

    #[On('ConfirmToBulkDeleteSkills')]
    public function bulkDeleteSkills(): void
    {
        $items = $this->selectedSkills();

        if ($items->isEmpty()) {
            return;
        }

        $deleted = 0;

        foreach ($items as $skill) {
            $skill->delete();
            $deleted++;
        }

        $this->refreshSkillsList();
        $this->adjustPaginationAfterDelete();

        $this->notification()->success(
            title: 'Suppression terminée',
            description: $deleted . ' compétence(s) supprimée(s).',
        );
    }
}
