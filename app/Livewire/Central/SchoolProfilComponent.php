<?php

namespace App\Livewire\Central;

use App\Livewire\Central\Actions\ActionsTraits;
use App\Models\Plan;
use App\Models\SubscriptionRequest;
use App\Models\Tenant;
use App\Services\Subscriptions\SubscriptionService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.central-auth-layout')]
#[Title("Profil école")]
class SchoolProfilComponent extends Component
{
    public ?string $school;

    use WireUiActions, ActionsTraits;

    
    // ─── État du formulaire d'octroi gratuit ───────────────────────────
    public bool $showGrantFreeModal = false;
    public ?int $grantPlanId = null;
    public int $grantDaysCount = 30;

    public function mount(?string $school)
    {
        $this->school = $school;

    }


    #[Computed]
    public function tenant()
    {
        return Tenant::find($this->school);
    }
    
    #[Computed]
    public function infos()
    {

        $scheme = request()->getScheme() ?? 'http';

        $port   = request()->getPort() && request()->getPort() != 80 && request()->getPort() != 443 
                ? ':' . request()->getPort() 
                : '';

        $baseUrl = $scheme . '://' . $this->tenant->domain_name . $port;

        $domain = rtrim($baseUrl, '/');

        $director_name = $this->tenant->getUserNamePrefix() . " " . $this->tenant->getFullName();

        $subcription_active = 'Aucun abonnement actif';

        if($this->activeSubscription){

            $subcription_active = 'Actif jusqu’au ' . __formatDateTime($this->activeSubscription->expire_at);
        }

        $infos = [
            ['Directeur', $director_name, 'user-round'],
            ['Statut abonnement', $subcription_active, 'badge-check'], 
            ['Base de données', '4.8 GB utilisées', 'database'], 
            ['Nom domaine', $domain, 'globe'], 
            ['Créée le', __formatDate($this->tenant->created_at), 'calendar-days']
        ];

        return $infos;
    }
    
    #[Computed]
    public function profil_photo_url()
    {
         /** @var \App\Models\CentralUser $central */
        $central = auth('central')->user();

        return $central->getTenantProfilPhotoUrl($this->school);
    }

    public function openGrantFreeModal(): void
    {
        $this->grantPlanId = null;
        $this->grantDaysCount = 30;
        $this->resetErrorBag();
        $this->showGrantFreeModal = true;
    }

    public function closeGrantFreeModal(): void
    {
        $this->showGrantFreeModal = false;
    }

    public function confirmGrantFreeSubscription(): void
    {
        $this->validate([
            'grantPlanId' => 'required|exists:plans,id',
            'grantDaysCount' => 'required|integer|min:1|max:3650',
        ], [
            'grantPlanId.required' => 'Merci de choisir un plan.',
        ]);

        $plan = Plan::findOrFail($this->grantPlanId);

        $this->dispatch('swal', [
            'title' => 'Offrir cet abonnement ?',
            'text' => "Le plan « {$plan->name} » sera activé gratuitement pour {$this->grantDaysCount} jours, pour « {$this->tenant->school_name} ».",
            'icon' => 'question',
            'showCancelButton' => true,
            'confirmButtonText' => 'Oui, offrir',
            'cancelButtonText' => 'Annuler',
            'onConfirmed' => 'grantFreeSubscription',
            'onConfirmedParams' => ['planId' => $this->grantPlanId, 'daysCount' => $this->grantDaysCount],
        ]);
    }

    #[On('grantFreeSubscription')]
    public function grantFreeSubscription(int $planId, int $daysCount, SubscriptionService $service): void
    {
        $plan = Plan::findOrFail($planId);

        $done = $service->grantFree($this->tenant->id, $planId, $daysCount);

        $this->closeGrantFreeModal();

        if($done){

            $this->notification()->success('Abonnement offert', "Le plan « {$plan->name} » a été activé gratuitement pour {$daysCount} jours.");
        }
        else{

            $this->notification()->error('Abonnement non offert', "Une erreure s'est produite!");
        }
    }

    #[Computed]
    public function activeSubscription()
    {
        return $this->tenant->activeSubscription;
    }


    #[Computed]
    public function plans()
    {
        return Plan::active()->ordered()->get();
    }

    #[Computed]
    public function demandes()
    {
        $tenantId = $this->school;

        return SubscriptionRequest::with('plan')
                ->forTenant($tenantId)
                ->where('status', '<>', 'active')
                ->latest()
                ->get();
    }

    
    public function render()
    {
        return view('livewire.central.school-profil-component');
    }
}
