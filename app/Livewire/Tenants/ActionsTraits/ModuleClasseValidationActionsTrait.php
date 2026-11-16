<?php

namespace App\Livewire\Tenants\ActionsTraits;

use App\Models\Classe;
use App\Models\ModuleClasseValidation;
use App\Models\SchoolYear;
use App\Models\Subject;
use Livewire\Attributes\On;

/**
 * Actions métier pour ModuleClasseValidation :
 * seuil de validation d’un module par classe et par année scolaire.
 *
 * Prérequis sur le composant hôte :
 * - WireUiActions (pour $this->notification())
 * - propriété $selectedClasseId (int|null)
 * - propriété $schoolYearId (int|null) — par défaut année active
 * - méthode / computed pour rafraîchir la liste
 *
 * Surcharges utiles :
 * - findModuleClasseValidationForManage()
 * - canManageModuleClasseValidations()
 * - getActiveSchoolYearId()
 */
trait ModuleClasseValidationActionsTrait
{
    protected function moduleClasseValidationDeleteConfirmedEvent(): string
    {
        return 'ConfirmToDeleteModuleClasseValidation';
    }

    protected function canManageModuleClasseValidations(): bool
    {
        return true; // côté directeur
    }

    protected function getActiveSchoolYearId(): ?int
    {
        return SchoolYear::current()?->first()?->id;
    }

    protected function findModuleClasseValidationForManage(int $id): ?ModuleClasseValidation
    {
        return ModuleClasseValidation::query()->where('id', $id)->first();
    }

    protected function findModuleForValidation(int $moduleId): ?Subject
    {
        return Subject::query()
            ->where('id', $moduleId)
            ->where('is_module', true)
            ->first();
    }

    protected function findClasseForValidation(int $classeId): ?Classe
    {
        return Classe::query()->where('id', $classeId)->first();
    }

    protected function refreshModuleClasseValidationsList(): void
    {
        if (property_exists($this, 'validations') || method_exists($this, 'validations')) {
            unset($this->validations);
        }
        if (property_exists($this, 'modulesForClasse') || method_exists($this, 'modulesForClasse')) {
            unset($this->modulesForClasse);
        }
    }

    // ─── Création / Upsert ────────────────────────────────────────────

    /**
     * Crée ou met à jour le seuil de validation pour un trio année / module / classe.
     *
     * @param  array{coef?: int|null, seuil?: float|null}  $data
     */
    public function saveModuleClasseValidation(
        int $moduleId,
        int $classeId,
        ?int $schoolYearId = null,
        array $data = []
    ): void {
        $schoolYearId = $schoolYearId ?? $this->getActiveSchoolYearId();

        if (! $schoolYearId) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Aucune année scolaire active.',
            );

            return;
        }

        $module = $this->findModuleForValidation($moduleId);

        if (! $module) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Module introuvable ou ce n\'est pas un module (is_module=false).',
            );

            return;
        }

        $classe = $this->findClasseForValidation($classeId);

        if (! $classe) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Classe introuvable.',
            );

            return;
        }

        $seuil = array_key_exists('seuil', $data)
            ? (float) $data['seuil']
            : 10.0;

        $coef = array_key_exists('coef', $data)
            ? ($data['coef'] !== null && $data['coef'] !== '' ? (int) $data['coef'] : null)
            : null;

        if ($seuil < 0 || $seuil > 20) {
            $this->notification()->error(
                title: 'Seuil invalide',
                description: 'Le seuil doit être compris entre 0 et 20.',
            );

            return;
        }

        ModuleClasseValidation::updateOrCreate(
            [
                'school_year_id' => $schoolYearId,
                'module_id'      => $moduleId,
                'classe_id'      => $classeId,
            ],
            [
                'seuil' => $seuil,
                'coef'  => $coef,
            ]
        );

        $this->refreshModuleClasseValidationsList();

        $this->notification()->success(
            title: 'Seuil enregistré',
            description: 'Le seuil de validation du module a été enregistré pour cette classe.',
        );
    }

    /**
     * Mise à jour d’un enregistrement existant (seuil / coef).
     *
     * @param  array{coef?: int|null, seuil?: float|null}  $data
     */
    public function updateModuleClasseValidation(int $id, array $data): void
    {
        $row = $this->findModuleClasseValidationForManage($id);

        if (! $row) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Configuration introuvable.',
            );

            return;
        }

        $payload = [];

        if (array_key_exists('seuil', $data)) {
            $seuil = (float) $data['seuil'];
            if ($seuil < 0 || $seuil > 20) {
                $this->notification()->error(
                    title: 'Seuil invalide',
                    description: 'Le seuil doit être compris entre 0 et 20.',
                );

                return;
            }
            $payload['seuil'] = $seuil;
        }

        if (array_key_exists('coef', $data)) {
            $payload['coef'] = $data['coef'] !== null && $data['coef'] !== ''
                ? (int) $data['coef']
                : null;
        }

        if ($payload === []) {
            return;
        }

        $row->update($payload);

        $this->refreshModuleClasseValidationsList();

        $this->notification()->success(title: 'Seuil mis à jour.');
    }

    // ─── Suppression ──────────────────────────────────────────────────

    public function confirmDeleteModuleClasseValidation(int $id): void
    {
        $row = $this->findModuleClasseValidationForManage($id);

        if (! $row) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Configuration introuvable.',
            );

            return;
        }

        $moduleName = $row->module?->name ?? 'ce module';

        $this->dispatch('swal', [
            'title'              => 'Supprimer ce seuil ?',
            'description'        => "Le seuil de validation pour « {$moduleName} » sera supprimé. Le module restera assigné à la classe.",
            'icon'               => 'question',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#06b6d4',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => $this->moduleClasseValidationDeleteConfirmedEvent(),
            'onConfirmedParams'  => ['id' => $id],
        ]);
    }

    #[On('ConfirmToDeleteModuleClasseValidation')]
    public function deleteModuleClasseValidation(int $id): void
    {
        $row = $this->findModuleClasseValidationForManage($id);

        if (! $row) {
            $this->notification()->error(
                title: 'Action impossible',
                description: 'Configuration introuvable.',
            );

            return;
        }

        $row->delete();

        $this->refreshModuleClasseValidationsList();

        $this->notification()->success(title: 'Seuil de validation supprimé.');
    }
}
