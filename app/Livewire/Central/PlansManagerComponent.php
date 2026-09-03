<?php
// app/Livewire/Central/PlansManagerComponent.php

namespace App\Livewire\Central;

use App\Events\CentralDataUpdatedEvent;
use App\Events\TenantDirectorDataUpdatedEvent;
use App\Livewire\Central\CentralTraits\CentralReloaderTrait;
use App\Models\Plan;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.central-auth-layout')]
#[Title("Gestion des packs et plans")]
class PlansManagerComponent extends Component
{
   
    use WireUiActions, CentralReloaderTrait;

    // ─── État du formulaire ─────────────────────────────────────────
    public bool $showForm = false;
    public ?int $editingPlanId = null;

    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public ?int $price = null;
    public int $days_count = 365;
    public string $pack = 'starter';
    public bool $is_active = true;
    public int $order = 0;


    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . $this->editingPlanId,
            'description' => 'nullable|string|max:1000',
            'price' => 'required|integer|min:0',
            'days_count' => 'required|integer|min:1',
            'pack' => 'required|in:starter,pro,premium,custom',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ];
    }

    public function updatedName(string $value): void
    {
        if (! $this->editingPlanId) {
            $this->slug = Str::slug($value);
        }
    }

    public function openCreateForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editPlan(int $planId): void
    {
        $plan = Plan::findOrFail($planId);

        $this->editingPlanId = $plan->id;
        $this->name = $plan->name;
        $this->slug = $plan->slug;
        $this->description = $plan->description ?? '';
        $this->price = $plan->price;
        $this->days_count = $plan->days_count;
        $this->pack = $plan->pack;
        $this->is_active = $plan->is_active;
        $this->order = $plan->order;

        $this->showForm = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->editingPlanId) {
            $plan = Plan::findOrFail($this->editingPlanId);
            $plan->update($validated);
            $this->notification()->success('Plan mis à jour', "Le plan « {$plan->nom} » a été mis à jour.");
        } else {
            $plan = Plan::create($validated);
            $this->notification()->success('Plan créé', "Le plan « {$plan->nom} » a été créé.");
        }

        $this->resetForm();
        $this->showForm = false;

        broadcast(new CentralDataUpdatedEvent());

        broadcast(new TenantDirectorDataUpdatedEvent());
    }

    public function cancelForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    protected function resetForm(): void
    {
        $this->reset(['editingPlanId', 'name', 'slug', 'description', 'price', 'order']);
        $this->days_count = 365;
        $this->pack = 'starter';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    // ─── Activation / suppression ─────────────────────────────────────

    public function toggleActive(int $planId): void
    {
        $plan = Plan::findOrFail($planId);
        $plan->update(['is_active' => ! $plan->is_active]);

        $this->notification()->success(
            $plan->is_active ? 'Plan activé' : 'Plan désactivé',
            "Le plan « {$plan->nom} » est maintenant " . ($plan->is_active ? 'actif' : 'inactif') . '.'
        );
    }

    public function confirmDelete(int $planId): void
    {
        $plan = Plan::findOrFail($planId);

        $this->dispatch('swal', [
            'title' => 'Supprimer ce plan ?',
            'text' => "Le plan « {$plan->nom} » sera définitivement supprimé. Cette action est irréversible.",
            'icon' => 'warning',
            'showCancelButton' => true,
            'confirmButtonText' => 'Oui, supprimer',
            'cancelButtonText' => 'Annuler',
            'onConfirmed' => 'deletePlan',
            'onConfirmedParams' => ['planId' => $planId],
        ]);
    }

    #[On('deletePlan')]
    public function deletePlan(int $planId): void
    {
        $plan = Plan::findOrFail($planId);

        if ($plan->subscriptionRequests()->exists() || $plan->subscriptions()->exists()) {
            $this->notification()->error(
                'Suppression impossible',
                'Ce plan est déjà utilisé par des demandes ou abonnements existants. Désactivez-le plutôt.'
            );

            return;
        }

        $name = $plan->name;

        $plan->delete();

        broadcast(new CentralDataUpdatedEvent());

        broadcast(new TenantDirectorDataUpdatedEvent());

        $this->notification()->success('Plan supprimé', "Le plan « {$name} » a été supprimé.");
    }

    #[Computed]
    public function plans()
    {
        return Plan::ordered()->get();
    }

    public function render()
    {
        return view('livewire.central.plans-manager-component');
    }
}