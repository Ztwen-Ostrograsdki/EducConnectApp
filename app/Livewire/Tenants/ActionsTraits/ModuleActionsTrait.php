<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Models\ModuleSkills;
use App\Models\Skills;
use App\Models\Subject;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;

/**
 * Actions métier pour lier / délier des compétences (Skills) à un module (Subject is_module=true).
 *
 * Prérequis sur le composant hôte :
 * - WireUiActions (pour $this->notification())
 * - propriété $selectedModuleId (int|null)
 * - propriété / computed pour rafraîchir la liste des liaisons
 *
 * Surcharges utiles :
 * - findModuleForManage()
 * - canManageModules()
 */
trait ModuleActionsTrait
{
    protected function moduleSkillUnlinkConfirmedEvent(): string
    {
        return 'ConfirmToUnlinkModuleSkill';
    }

    protected function canManageModules(): bool
    {
        return true; // côté directeur
    }

    protected function findModuleForManage(int $subjectId): ?Subject
    {
        return Subject::query()
            ->where('id', $subjectId)
            ->where('is_module', true)
            ->first();
    }

    protected function findModuleSkill(int $moduleSkillId): ?ModuleSkills
    {
        return ModuleSkills::query()->where('id', $moduleSkillId)->first();
    }

    protected function refreshModuleSkillsList(): void
    {
        if (property_exists($this, 'linkedSkills')) {
            unset($this->linkedSkills);
        }
        if (method_exists($this, 'resetPage')) {
            // ne force pas forcément
        }
    }

    // ─── Lier une compétence à un module ──────────────────────────────

    /**
     * Lie un skill existant à un module.
     * $data peut contenir code, weight, coef, is_active.
     */
    public function linkSkillToModule(int $subjectId, int $skillId, array $data = []): void
    {
        $module = $this->findModuleForManage($subjectId);

        if (! $module) {
            $this->notification()->error(
                title: 'Liaison impossible',
                description: 'Module introuvable ou ce n\'est pas un module (is_module=false).',
            );

            return;
        }

        $skill = Skills::query()->where('id', $skillId)->first();

        if (! $skill) {
            $this->notification()->error(
                title: 'Liaison impossible',
                description: 'Compétence introuvable.',
            );

            return;
        }

        $exists = ModuleSkills::query()
            ->where('subject_id', $subjectId)
            ->where('skill_id', $skillId)
            ->exists();

        if ($exists) {
            $this->notification()->warning(
                title: 'Déjà liée',
                description: 'Cette compétence est déjà liée à ce module.',
            );

            return;
        }

        ModuleSkills::create([
            'subject_id' => $subjectId,
            'skill_id'   => $skillId,
            'code'       => $data['code'] ?? $skill->code,
            'weight'     => $data['weight'] ?? $skill->weight,
            'coef'       => $data['coef'] ?? $skill->coef,
            'is_active'  => $data['is_active'] ?? true,
        ]);

        $this->refreshModuleSkillsList();

        $this->notification()->success(
            title: 'Compétence liée',
            description: 'La compétence a été associée au module avec succès.',
        );
    }

    // ─── Délier ───────────────────────────────────────────────────────

    public function confirmUnlinkModuleSkill(int $moduleSkillId): void
    {
        $ms = $this->findModuleSkill($moduleSkillId);

        if (! $ms) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Liaison introuvable.',
            );

            return;
        }

        $this->dispatch('swal', [
            'title'              => 'Délier cette compétence ?',
            'description'        => 'La compétence ne sera plus associée à ce module. La compétence elle-même ne sera pas supprimée.',
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, délier',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#06b6d4',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => $this->moduleSkillUnlinkConfirmedEvent(),
            'onConfirmedParams'  => ['moduleSkillId' => $moduleSkillId],
        ]);
    }

    #[On('ConfirmToUnlinkModuleSkill')]
    public function unlinkModuleSkill(int $moduleSkillId): void
    {
        $ms = $this->findModuleSkill($moduleSkillId);

        if (! $ms) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Liaison introuvable.',
            );

            return;
        }

        $ms->delete();

        $this->refreshModuleSkillsList();

        $this->notification()->success(title: 'Compétence déliée du module.');
    }

    /**
     * Délier rapidement sans confirmation (utile depuis un bouton avec wire:confirm natif ou autre).
     */
    public function unlinkSkillFromModule(int $moduleSkillId): void
    {
        $this->unlinkModuleSkill($moduleSkillId);
    }

    // ─── Toggle active sur la liaison ─────────────────────────────────

    public function toggleModuleSkillActive(int $moduleSkillId): void
    {
        $ms = $this->findModuleSkill($moduleSkillId);

        if (! $ms) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Liaison introuvable.',
            );

            return;
        }

        $ms->update([
            'is_active' => ! (bool) $ms->is_active,
        ]);

        $this->refreshModuleSkillsList();

        $active = (bool) $ms->fresh()->is_active;

        $this->notification()->success(
            title: $active ? 'Liaison activée' : 'Liaison désactivée',
        );
    }

    // ─── Helpers de listing ───────────────────────────────────────────

    /**
     * Compétences déjà liées à un module.
     *
     * @return Collection<int, ModuleSkills>
     */
    protected function getLinkedModuleSkills(int $subjectId): Collection
    {
        return ModuleSkills::query()
            ->with(['skill'])
            ->where('subject_id', $subjectId)
            ->orderBy('id')
            ->get();
    }

    /**
     * Compétences disponibles (non encore liées) pour un module.
     *
     * @return Collection<int, Skills>
     */
    protected function getAvailableSkillsForModule(int $subjectId): Collection
    {
        $linkedIds = ModuleSkills::query()
            ->where('subject_id', $subjectId)
            ->pluck('skill_id');

        return Skills::query()
            ->active()
            ->whereNotIn('id', $linkedIds)
            ->orderBy('content')
            ->get();
    }
}
