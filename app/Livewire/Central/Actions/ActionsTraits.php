<?php

namespace App\Livewire\Central\Actions;

use App\Events\SendCredentialsToCreatedTenantEvent;
use App\Events\TenantAccessWasUpdatedEvent;
use App\Events\TenantSpaceWasBlockedEvent;
use App\Jobs\JobToCreateTenantSpace;
use App\Models\RequestToCreateNewTenant;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use WireUi\Traits\WireUiActions;




trait ActionsTraits{

	use WireUiActions;

	public function sendCredentialsToTenant(string $domain)
    {
        $req = RequestToCreateNewTenant::firstWhere('domain_name', $domain);

        if($req && $req->validated){

            $domain = $req->domain_name;

            $space_url = get_tenant_url($domain, 'login');

            $tenant = Tenant::where('domain_name', $domain)->firstOrFail();

            SendCredentialsToCreatedTenantEvent::dispatch($tenant->id, $space_url, false);

            $this->notification()->send([
                'icon'        => 'success',
                'title'       => "Envoi des données espaces ",
                'description' => "Les détails de l'espace tenant ont été envoyés à " . $req->getUserNamePrefix(true, false),
            ]);
        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Erreur processus',
                'description' => "La reqûete n'existe pas ou n'a pas encore été validée!",
            ]);
            
        }
    }

	public function validateRequest(int $requestId): void
    {
        $this->dispatch('swal', [
            'title'              => "Valider cette demande d'espace école ? ",
            'text'               => "Cette action permettra de créer un accès pour cette école. Le domaine de cette école sera créé et les données seront envoyées au demandeur!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Valider',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToValidateRequest',
            'onConfirmedParams'  => ['requestId' => $requestId],
        ]);
    }

    #[On('ConfirmToValidateRequest')]
    public function onValidateRequest(int $requestId): void
    {
        $req = RequestToCreateNewTenant::find($requestId);

        if (!$req) {

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Requête introuvable',
                'description' => "La reqûete n'existe pas dnas la base de données",
            ]);
        }

        try {
            
            $domain = $req->domain_name;

            $space_url = get_tenant_url($domain, 'login');

            JobToCreateTenantSpace::dispatch($req->id, $space_url);

            $this->notification()->send([
                'icon'        => 'success',
                'title'       => 'Validation lancée...!',
                'description' => "Le processus de la validation de la demande a été lancée.",
            ]);


        } catch (\Throwable $th) {
            $this->notification()->error(
                title: 'Demande non confirmée',
                description: "Une erreur est survenue : " . cutter($th->getMessage(), 2000),
            );
        }
        
    }

    public function deleteRequest(string $requestId): void
    {

       $this->dispatch('swal', [
            'title'              => "Supprimer cette demande d'espace école ? ",
            'text'               => "Cette action permettra supprimera la demande!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, supprimer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToDeleteRequest',
            'onConfirmedParams'  => ['requestId' => $requestId],
        ]);

    }

	#[On('ConfirmToDeleteRequest')]
    public function onDeleteRequest(int $requestId): void
    {
        $req = RequestToCreateNewTenant::find($requestId);

        if($req){

            $del = $req->delete();

            if($del){
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'Suppression terminée',
                    'description' => "La reqûete a été supprimée avec succès!",
                ]);
            }
            else{
                $this->notification()->send([
                    'icon'        => 'warning',
                    'title'       => 'Echec de la suppression',
                    'description' => "La reqûete n'a pas été supprimée!",
                ]);
            }

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Requête introuvable',
                'description' => "La reqûete n'existe pas dans la base de données",
            ]);
            
        }
        
    }

    
    public function rejectRequest(string $requestId): void
    {
        $this->dispatch('swal', [
            'title'              => "Rejeter ou suspendre cette demande d'espace école ? ",
            'text'               => "Cette action mettra la demande en suspend. La demande sera considérée comme rejétée!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, rejeter',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRejectRequest',
            'onConfirmedParams'  => ['requestId' => $requestId],
        ]);
    }

	#[On('ConfirmToRejectRequest')]
    public function onConfirmRequestReject(int $requestId): void
    {
        $req = RequestToCreateNewTenant::find($requestId);

        if($req && !$req->validated){

            $suspended = $req->update(['status' => 'suspended']);

            if($suspended){
                $this->notification()->send([
                    'icon'        => 'success',
                    'title'       => 'Revocation terminée',
                    'description' => "La reqûete a été révoquée avec succès!",
                ]);
            }
            else{
                $this->notification()->send([
                    'icon'        => 'warning',
                    'title'       => 'Echec de la Revocation',
                    'description' => "La reqûete n'a pas été Revoquée!",
                ]);
            }

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Requête introuvable',
                'description' => "La reqûete n'existe pas dans la base de données",
            ]);
            
        }
    }


	public function suspend(string $requestId): void
    {
        $tenant = getTenant($requestId);

        $tenant->update([
            'domain_blocked' => true,
        ]);

    }

    public function unsuspend(string $requestId): void
    {

        $this->notification()->success(
            'Succès',
            'Domaine du tenant bloqué!'
        );

    }


	public function blockDomain(string $tenantId): void
    {

		$this->dispatch('swal', [
            'title'              => "Bloquer le domaine de cette école? ",
            'text'               => "L'espace de cette école ne sera plus accessible!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, bloquer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToBlockDomain',
            'onConfirmedParams'  => ['tenantId' => $tenantId],
        ]);
       
    }

	#[On('ConfirmToBlockDomain')]
    public function onBlockDomain(string $tenantId): void
    {
        $tenant = getTenant($tenantId);

        $tenant->update([
            'domain_blocked' => true,
        ]);

        $this->notification()->success(
            'Succès',
            'Domaine du tenant bloqué!'
        );

        broadcast(new TenantAccessWasUpdatedEvent($tenantId));

        broadcast(new TenantSpaceWasBlockedEvent($tenantId));
        
    }

    public function unblockDomain(string $tenantId): void
    {
        $this->dispatch('swal', [
            'title'              => "Débloquer le domaine de cette école? ",
            'text'               => "L'espace de cette école sera de nouveau accessible!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, débloquer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToUnblockDomain',
            'onConfirmedParams'  => ['tenantId' => $tenantId],
        ]);

    }


    #[On('ConfirmToUnblockDomain')]
    public function onUnblockDomain(string $tenantId): void
    {
        $tenant = getTenant($tenantId);

        $tenant->update([
            'domain_blocked' => false,
        ]);

        $this->notification()->success(
            'Succès',
            'Domaine du tenant débloqué!'
        );

        broadcast(new TenantAccessWasUpdatedEvent($tenantId));
        
    }


    public function deleteTenant(string $tenantId): void
    {
        $this->dispatch('swal', [
            'title'              => "Envoyer cette école dans la corbeille ? ",
            'text'               => "L'espace de cette école ne sera plus accessible!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, dans la corbeille',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToPutIntoTrashed',
            'onConfirmedParams'  => ['tenantId' => $tenantId],
        ]);

    }

	#[On('ConfirmToPutIntoTrashed')]
    public function OnTrashedTenant(string $tenantId): void
    {
        $tenant = Tenant::find($tenantId);

        if($tenant){

			try {
				DB::transaction(function () use ($tenantId){

					$tenant = getTenant($tenantId);

					$tenant->update([
						'domain_blocked' => true,
					]);

					$del = $tenant->delete();

					if($del){
						$this->notification()->send([
							'icon'        => 'success',
							'title'       => 'Mise en corbeille terminée',
							'description' => "L'école a été mise en corbeille avec succès!",
						]);
					}
					else{
						$this->notification()->send([
							'icon'        => 'warning',
							'title'       => 'Echec de la mise en corbeille',
							'description' => "Le tenant n'a pas été mise en corbeille!",
						]);
					}
					
				});
			} catch (\Throwable $th) {

				$message = cutter($th->getMessage(), 2000);

				$this->notification()->send([
					'icon'        => 'warning',
					'title'       => 'Echec de la mise en corbeille',
					'description' => $message,
				]);
			}
			finally{

				broadcast(new TenantAccessWasUpdatedEvent($tenantId));

				broadcast(new TenantSpaceWasBlockedEvent($tenantId));


			}

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Espace école introuvable',
                'description' => "Le tenant ou cette école n'existe pas dans la base de données",
            ]);
            
        }
        
    }

    public function restoreTenant(string $tenantId): void
    {
		$this->dispatch('swal', [
            'title'              => "Restorer cette école de la corbeille ? ",
            'text'               => "L'espace de cette école sera de nouveau accessible!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, restorer',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToRestoreTenant',
            'onConfirmedParams'  => ['tenantId' => $tenantId],
        ]);
    }

	#[On('ConfirmToRestoreTenant')]
    public function onRestoreTenant(string $tenantId): void
    {
        $tenant = Tenant::withTrashed()->whereId($tenantId)->first();

        if($tenant){

            try {
				DB::transaction(function () use ($tenantId){

					$tenant = getTenant($tenantId);

					$tenant->update([
						'domain_blocked' => false,
					]);

					$del = $tenant->restore();

					if($del){
						$this->notification()->send([
							'icon'        => 'success',
							'title'       => 'Restoration terminée',
							'description' => "L'école a été mise en restorée avec succès!",
						]);
					}
					else{
						$this->notification()->send([
							'icon'        => 'warning',
							'title'       => 'Echec de la restoration',
							'description' => "Le tenant n'a pas été restorée!",
						]);
					}
					
				});
			} catch (\Throwable $th) {

				$message = cutter($th->getMessage(), 2000);

				$this->notification()->send([
					'icon'        => 'warning',
					'title'       => 'Echec de la restoration',
					'description' => $message,
				]);
			}
			finally{

				broadcast(new TenantAccessWasUpdatedEvent($tenantId));


			}

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'tenant introuvable',
                'description' => "Le tenant n'existe pas dans la base de données",
            ]);
            
        }
        
    }

    public function forceDelete(string $tenantId): void
    {
		$this->dispatch('swal', [
            'title'              => "Supprimer définitivement cette école ? ",
            'text'               => "L'espace de cette école sera supprimée définitivement dissout et supprimée!",
            'icon'               => 'warning',
            'showCancelButton'   => true,
            'confirmButtonText'  => 'Oui, Supprimer définitivement',
            'cancelButtonText'   => 'Annuler',
            'confirmButtonColor' => '#f97316',
            'cancelButtonColor'  => '#475569',
            'onConfirmed'        => 'ConfirmToForceDelete',
            'onConfirmedParams'  => ['tenantId' => $tenantId],
        ]);
    }

	#[On("ConfirmToForceDelete")]
    public function ConfirmSchoolForceDelete(string $tenantId): void
    {
		$tenant = Tenant::withTrashed()->whereId($tenantId)->first();

       if($tenant){

			try {
				DB::transaction(function () use ($tenantId){

					$tenant = getTenant($tenantId);

					$tenant->update([
						'domain_blocked' => true,
					]);

					$del = $tenant->forceDelete();

					if($del){
						$this->notification()->send([
							'icon'        => 'success',
							'title'       => 'Suppresion définitive lancée',
							'description' => "La suppresion de l'école a été planifiée!",
						]);
					}
					else{
						$this->notification()->send([
							'icon'        => 'warning',
							'title'       => 'Echec de la suppresion définitive',
							'description' => "Le tenant n'a pas été supprimée!",
						]);
					}
					
				});
			} catch (\Throwable $th) {

				$message = cutter($th->getMessage(), 2000);

				$this->notification()->send([
					'icon'        => 'warning',
					'title'       => 'Echec de la suppresion définitive',
					'description' => $message,
				]);
			}
			finally{

				broadcast(new TenantAccessWasUpdatedEvent($tenantId));

                broadcast(new TenantSpaceWasBlockedEvent($tenantId));


			}

        }
        else{

            $this->notification()->send([
                'icon'        => 'error',
                'title'       => 'Espace école introuvable',
                'description' => "Le tenant ou cette école n'existe pas dans la base de données",
            ]);
            
        }
        
    }
}