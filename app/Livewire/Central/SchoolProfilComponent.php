<?php

namespace App\Livewire\Central;

use App\Livewire\Central\Actions\ActionsTraits;
use App\Models\Tenant;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

#[Layout('livewire.layouts.central-auth-layout')]
#[Title("Profil école")]
class SchoolProfilComponent extends Component
{
    public ?string $school;

    use WireUiActions, ActionsTraits;

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

        $infos = [
            ['Directeur', $director_name, 'user-round'],
            ['Statut abonnement', 'Actif jusqu’au 20 Nov 2026', 'badge-check'], 
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

    
    public function render()
    {
        return view('livewire.central.school-profil-component');
    }
}
