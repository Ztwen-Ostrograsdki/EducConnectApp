<?php

namespace App\Livewire\Central;

use App\Events\CentralDataUpdatedEvent;
use App\Events\TenantModulesAccessesUpdatedEvent;
use App\Livewire\Central\CentralTraits\CentralReloaderTrait;
use App\Models\Subscription;
use App\Models\TenantModuleAccess;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.central-auth-layout')]
#[Title('Gestion des modules')]
class ManageSubscriptionModules extends Component
{
    use CentralReloaderTrait, WireUiActions;

    #[Locked]
    public int|string|null $subscriptionId = null;

    public string $selectedPack = '';

    public function mount(int|string $subscriptionId): void
    {
        $this->subscriptionId = $subscriptionId;

        $access = $this->moduleAccess;

        if (! $access) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Introuvable',
                'description' => 'Aucun accès modules lié à cette subscription.',
            ]);

            return;
        }

        $this->selectedPack = $access->pack ?? 'custom';
    }

    #[Computed]
    public function subscription(): ?Subscription
    {
        return Subscription::query()
            ->with(['tenant', 'plan', 'moduleAccess'])
            ->find($this->subscriptionId);
    }

    #[Computed]
    public function moduleAccess(): ?TenantModuleAccess
    {
        $subscription = $this->subscription;

        if (! $subscription) {
            return null;
        }

        return $subscription->moduleAccess
            ?? TenantModuleAccess::query()
                ->where('subscription_id', $subscription->id)
                ->first();
    }

    #[Computed]
    public function canEdit(): bool
    {
        return $this->moduleAccess?->isEditable() ?? false;
    }

    #[Computed]
    public function modulesByCategory(): array
    {
        return $this->moduleAccess?->modulesByCategory() ?? [];
    }

    #[Computed]
    public function availablePacks(): array
    {
        return array_keys(TenantModuleAccess::packs());
    }

    // ─── Toggle module ────────────────────────────────────────────────

    public function toggleModule(string $module): void
    {
        if (! $this->canEdit) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Action refusée',
                'description' => 'La subscription est expirée ou introuvable. Modification impossible.',
            ]);

            return;
        }

        if (! array_key_exists($module, TenantModuleAccess::moduleLabels())) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Module invalide',
                'description' => "Le module « {$module} » n'existe pas.",
            ]);

            return;
        }

        $access = $this->moduleAccess;
        $label = TenantModuleAccess::moduleLabels()[$module]['label'] ?? $module;
        $currentlyEnabled = $access?->hasModule($module) ?? false;
        $action = $currentlyEnabled ? 'désactiver' : 'activer';

        $this->dispatch('swal', [
            'title' => ucfirst($action) . " le module « {$label} » ?",
            'text' => $currentlyEnabled
                ? "Ce module ne sera plus disponible pour l'établissement."
                : "Ce module sera disponible pour l'établissement.",
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Oui, ' . $action,
            'cancelButtonText' => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor' => '#475569',
            'onConfirmed' => 'ConfirmToggleModule',
            'onConfirmedParams' => ['module' => $module],
        ]);
    }

    #[On('ConfirmToggleModule')]
    public function onConfirmToggleModule(string $module): void
    {
        $access = $this->moduleAccess;

        if (! $access || ! $this->canEdit) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Action refusée',
                'description' => 'La subscription est expirée ou introuvable.',
            ]);

            return;
        }

        if (! array_key_exists($module, TenantModuleAccess::moduleLabels())) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Module invalide',
                'description' => "Le module « {$module} » n'existe pas.",
            ]);

            return;
        }

        $tenantId = $access->tenant_id;

        try {
            $enabled = $access->toggleModule($module);
            $this->selectedPack = 'custom';

            unset($this->moduleAccess, $this->modulesByCategory, $this->subscription);

            $label = TenantModuleAccess::moduleLabels()[$module]['label'] ?? $module;

            $this->notification()->send([
                'icon' => 'success',
                'title' => $enabled ? 'Module activé' : 'Module désactivé',
                'description' => "« {$label} » a été " . ($enabled ? 'activé' : 'désactivé') . '.',
            ]);
        } catch (\Throwable $th) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Échec de la mise à jour',
                'description' => cutter($th->getMessage(), 2000),
            ]);
        }
        finally{

            broadcast(new CentralDataUpdatedEvent());

            broadcast(new TenantModulesAccessesUpdatedEvent($tenantId));
        }
    }

    // ─── Appliquer un pack ────────────────────────────────────────────

    public function applyPack(): void
    {
        if (! $this->canEdit) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Action refusée',
                'description' => 'La subscription est expirée ou introuvable. Modification impossible.',
            ]);

            return;
        }

        if (! array_key_exists($this->selectedPack, TenantModuleAccess::packs())) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Pack invalide',
                'description' => 'Le pack sélectionné n’existe pas.',
            ]);

            return;
        }

        $pack = $this->selectedPack;

        $this->dispatch('swal', [
            'title' => "Appliquer le pack « {$pack} » ?",
            'text' => 'Tous les modules seront réinitialisés selon ce pack. Les réglages manuels (custom) seront perdus.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Oui, appliquer',
            'cancelButtonText' => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor' => '#475569',
            'onConfirmed' => 'ConfirmApplyPack',
            'onConfirmedParams' => ['pack' => $pack],
        ]);
    }

    #[On('ConfirmApplyPack')]
    public function onConfirmApplyPack(string $pack): void
    {
        $access = $this->moduleAccess;

        if (! $access || ! $this->canEdit) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Action refusée',
                'description' => 'La subscription est expirée ou introuvable.',
            ]);

            return;
        }

        if (! array_key_exists($pack, TenantModuleAccess::packs())) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Pack invalide',
                'description' => 'Le pack sélectionné n’existe pas.',
            ]);

            return;
        }

        $tenantId = $access->tenant_id;

        try {
            $access->applyPack($pack);
            $this->selectedPack = $pack;

            unset($this->moduleAccess, $this->modulesByCategory, $this->subscription);

            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Pack appliqué',
                'description' => "Le pack « {$pack} » a été appliqué. Les modules ont été réinitialisés.",
            ]);
        } catch (\Throwable $th) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Échec de l’application du pack',
                'description' => cutter($th->getMessage(), 2000),
            ]);
        }
        finally{

            broadcast(new CentralDataUpdatedEvent());

            broadcast(new TenantModulesAccessesUpdatedEvent($tenantId));
        }
    }

    // ─── Catégorie entière ────────────────────────────────────────────

    public function enableAllInCategory(string $category): void
    {
        $this->confirmCategoryModules($category, true);
    }

    public function disableAllInCategory(string $category): void
    {
        $this->confirmCategoryModules($category, false);
    }

    protected function confirmCategoryModules(string $category, bool $enabled): void
    {
        if (! $this->canEdit) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Action refusée',
                'description' => 'La subscription est expirée ou introuvable.',
            ]);

            return;
        }

        $action = $enabled ? 'activer' : 'désactiver';

        $this->dispatch('swal', [
            'title' => ucfirst($action) . " tous les modules « {$category} » ?",
            'text' => "Tous les modules de la catégorie « {$category} » seront " . ($enabled ? 'activés' : 'désactivés') . '.',
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Oui, ' . $action,
            'cancelButtonText' => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor' => '#475569',
            'onConfirmed' => 'ConfirmCategoryModules',
            'onConfirmedParams' => [
                'category' => $category,
                'enabled' => $enabled,
            ],
        ]);
    }

    #[On('ConfirmCategoryModules')]
    public function onConfirmCategoryModules(string $category, bool $enabled): void
    {
        $access = $this->moduleAccess;

        if (! $access || ! $this->canEdit) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Action refusée',
                'description' => 'La subscription est expirée ou introuvable.',
            ]);

            return;
        }

        $tenantId = $access->tenant_id;

        try {
            $labels = TenantModuleAccess::moduleLabels();
            $updates = ['pack' => 'custom'];

            foreach ($labels as $key => $info) {
                if ($info['category'] === $category) {
                    $updates[$key] = $enabled;
                }
            }

            $access->update($updates);
            $this->selectedPack = 'custom';

            unset($this->moduleAccess, $this->modulesByCategory, $this->subscription);

            $this->notification()->send([
                'icon' => 'success',
                'title' => $enabled ? 'Catégorie activée' : 'Catégorie désactivée',
                'description' => "Tous les modules de « {$category} » ont été " . ($enabled ? 'activés' : 'désactivés') . '.',
            ]);
        } catch (\Throwable $th) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Échec de la mise à jour',
                'description' => cutter($th->getMessage(), 2000),
            ]);
        }
        finally{

            broadcast(new CentralDataUpdatedEvent());

            broadcast(new TenantModulesAccessesUpdatedEvent($tenantId));
        }
    }

    public function render()
    {
        return view('livewire.central.manage-subscription-modules');
    }
}
