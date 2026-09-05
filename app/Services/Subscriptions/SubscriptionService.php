<?php

namespace App\Services\Subscriptions;

use App\Events\CentralDataUpdatedEvent;
use App\Events\TenantDirectorDataUpdatedEvent;
use App\Exceptions\SubscriptionRequestActionException;
use App\Models\CentralUser;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use App\Models\Tenant;
use App\Models\TenantModuleAccess;
use App\Models\User;
use App\Notifications\CentralRealTimeNotification;
use App\Notifications\RealTimeNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SubscriptionService
{
    /**
     * Le tenant lance une demande d'abonnement pour un plan.
     * Aucun paiement en ligne : transaction_id reste nul.
     */
    public function createRequest(string $tenantId, int $planId): ?SubscriptionRequest
    {
        $done = SubscriptionRequest::create([
            'tenant_id' => $tenantId,
            'plan_id' => $planId,
            'transaction_id' => null,
            'status' => 'pending',
        ]);

        if($done){

            broadcast(new CentralDataUpdatedEvent());

            broadcast(new TenantDirectorDataUpdatedEvent($tenantId));

            return $done;
        }

        return null;

    }

    /**
     * Le tenant signale avoir payé, en renseignant l'ID de transaction.
     */
    public function claimPayment(SubscriptionRequest $request, string $transactionId): SubscriptionRequest
    {
        if (! $request->canClaimPayment()) {
            throw new SubscriptionRequestActionException(
                'Cette demande ne peut plus être modifiée.'
            );
        }

        try {
                $request->update([
                'transaction_id' => $transactionId,
                'statut' => 'payment_claimed',
            ]);


            $central = CentralUser::first();

            $central?->notify(new CentralRealTimeNotification(
                title:             "RECLAMATION DE VALIDATION",
                message:           "Vous avez reçu une nouvelle reclamation de validation d'abonnement, à priori déjà soldée",
                type:              'success',
            ));


        } catch (\Throwable $th) {
           
            if($request){

                $tenant = Tenant::find($request->tenant_id);

                $director = null;

                $tenant->run(function() use (&$director){

                    $director = User::first();

                });

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $request->tenant_id,
                    title:             "RECLAMATION NON-ENVOYEE",
                    message:           "Une erreure est survenue: " . cutter($th->getMessage(), 2000),
                    type:              'error',
                ));

            }
        }
        finally{

            broadcast(new CentralDataUpdatedEvent());

            broadcast(new TenantDirectorDataUpdatedEvent($request->tenant_id));

        }

        return $request->fresh();
    }

    /**
     * Le central approuve la demande : crée/prolonge l'abonnement,
     * active les modules du plan, notifie le tenant.
     */
    public function approve(SubscriptionRequest $request, CentralUser $actor): Subscription
    {
        if (! $request->canBeActedOn()) {
            throw new SubscriptionRequestActionException(
                'Cette demande a déjà été traitée.'
            );
        }

        return DB::transaction(function () use ($request, $actor) {
            
            $plan = $request->plan;

            $subscription = Subscription::create([
                'tenant_id' => $request->tenant_id,
                'plan_id' => $plan->id,
                'subscription_request_id' => $request->id,
                'started_at' => now(),
                'expire_at' => now()->addDays($plan->days_count),
                'status' => 'active',
            ]);

            $request->update([
                'status' => 'approved',
                'treated_by' => $actor->id,
                'treated_at' => now(),
            ]);

            $moduleAccess = TenantModuleAccess::firstOrCreate(
                ['tenant_id' => $request->tenant_id],
            );

            $moduleAccess->applyPack($plan->pack);
            
            $moduleAccess->update(['pack_expires_at' => $subscription->expire_at]);

            DB::afterCommit(function () use ($request, $plan) {

				$tenant = Tenant::find($request->tenant_id);

                if(!$tenant) return;

                $central = CentralUser::first();

                $director = null;

                $tenant->run(function() use (&$director){

                    $director = User::first();

                });

                $central?->notify(new CentralRealTimeNotification(
                    title:             "DEMANDE APPROUVEE",
                    message:           "Vous avez approuvé la demande d'abonnement effectuée par {$director?->getFullName()} !",
                    type:              'success',
                ));

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $request->tenant_id,
                    title:             "DEMANDE D'ABONNEMENT APPROUVEE",
                    message:           "Votre demande d'abonnement au plan {$plan->name} a été approuvée!",
                    type:              'success',
                ));


                broadcast(new CentralDataUpdatedEvent());

                broadcast(new TenantDirectorDataUpdatedEvent($request->tenant_id));


                // $this->sendToTenant($request, new SubscriptionRequestApprovedMail($request));
            });

            return $subscription;
        });
    }

    /**
     * Le central rejette la demande, avec un motif obligatoire.
     */
    public function reject(SubscriptionRequest $request, CentralUser $actor, string $motif): SubscriptionRequest
    {
        if (! $request->canBeActedOn()) {
            throw new SubscriptionRequestActionException(
                'Cette demande a déjà été traitée.'
            );
        }

        $request->update([
            'status' => 'rejected',
            'reject_reason' => $motif,
            'treated_by' => $actor->id,
            'treated_at' => now(),
        ]);

        DB::afterCommit(function () use ($request) {

            $tenant = Tenant::find($request->tenant_id);

			if(!$tenant) return;

			$central = CentralUser::first();

			$director = null;

            $tenant->run(function() use (&$director){

                $director = User::first();

            });

			$central?->notify(new CentralRealTimeNotification(
				title:             "DEMANDE REJETEE",
				message:           "Vous avez rejété la demande effectuée par {$director?->getFullName()} !",
				type:              'success',
			));

			$director?->notify(new RealTimeNotification(
				userEmail: $director?->email,
				tenantId: $request->tenant_id,
				title:             "DEMANDE D'ABONNEMENT REJTEE",
				message:           "Votre demande d'abonnement a été rejétée!",
				type:              'warning',
			));

            broadcast(new CentralDataUpdatedEvent());

            broadcast(new TenantDirectorDataUpdatedEvent($request->tenant_id));


            // $this->sendToTenant($request, new SubscriptionRequestRejectedMail($request));
        });

        return $request->fresh();
    }

    /**
     * Le central réclame le paiement (relance) si le tenant n'a rien signalé.
     */
    public function remindPayment(SubscriptionRequest $request): SubscriptionRequest
    {
        if (! $request->isPending()) {
            throw new SubscriptionRequestActionException(
                'Le paiement a déjà été signalé ou la demande est déjà traitée.'
            );
        }

        $request->update(['payment_reminder_sent_at' => now()]);

        DB::afterCommit(function () use ($request) {

			$tenant = Tenant::find($request->tenant_id);

			if(!$tenant) return;

			$central = CentralUser::first();



			$director = null;

            $tenant->run(function() use (&$director){

                $director = User::first();

            });

			$central?->notify(new CentralRealTimeNotification(
				title:             "DEMANDE DE PAYEMENT ENVOYEE",
				message:           "La demande a été envoyée avec succès  a " . $director?->getUserNamePrefix(true, true) . "!",
				type:              'success',
			));

			$director?->notify(new RealTimeNotification(
				userEmail: $director?->email,
				tenantId: $request->tenant_id,
				title:             "PAYEMENT ABONNEMENT RECLAME",
				message:           "Vous avez recemment lancé une demande d'abonnement. Vous devez finaliser le payement afin que votre demande soit validée et que l'abonnement soit activé!",
				type:              'success',
			));


            broadcast(new CentralDataUpdatedEvent());

            broadcast(new TenantDirectorDataUpdatedEvent($request->tenant_id));

            // $this->sendToTenant($request, new SubscriptionPaymentReminderMail($request));
        });

        return $request->fresh();
    }

    /**
     * Suppression (soft delete) d'une demande par le central.
     */
    public function deleteRequest(SubscriptionRequest $request): void
    {
        $tenantId = $request->tenant_id;

        $request->forceDelete();

        broadcast(new CentralDataUpdatedEvent());

        broadcast(new TenantDirectorDataUpdatedEvent($tenantId));
    }


     /**
     * Suppression (soft delete) d'une demande par le central.
     */
    public function deleteSubscription(SubscriptionRequest $request): void
    {
        $tenantId = $request->tenant_id;

        try {
            
            DB::transaction(function() use ($request){

                if($request->subscription){

                    $request->subscription->forceDelete();
                }

                $request->forceDelete();

            });


        } catch (\Throwable $th) {

            $central  = CentralUser::first();

            $central?->notify(new CentralRealTimeNotification(
				title:             "ECHEC DE SUPPRESSION ABONNEMENT",
				message:           "La suppression de l'abonnement a échoué! : " . cutter($th->getMessage(), 2000),
				type:              'error',
			));
        }
        finally{

            broadcast(new CentralDataUpdatedEvent());

            broadcast(new TenantDirectorDataUpdatedEvent($tenantId));
        }
    }

    /**
     * Envoie un email au(x) directeur(s) du tenant.
     * Ajuste la résolution du destinataire selon comment tu récupères
     * l'email du directeur d'un tenant (colonne directe, ou via User tenant).
     */
    protected function sendToTenant(SubscriptionRequest $request, $mailable): void
	{
		$email = $request->tenant->email ?? null;

		if ($email) {
			Mail::to($email)->queue($mailable);
		}
	}


   /**
     * Le central offre un abonnement gratuit à un tenant, sans passer
     * par une SubscriptionRequest (octroi manuel, hors flux de demande).
     */
    public function grantFree(string $tenantId, int $planId, int $daysCount): ?Subscription
    {
        $tenant = Tenant::find($tenantId);

        $plan = Plan::find($planId);

        if(!$tenant || !$plan){


            $central  = CentralUser::first();

            $central?->notify(new CentralRealTimeNotification(
                title:             "ECHEC DE L'OCTROIE DE L'ABONNEMENT",
                message:           "Nous n'avons pas pu octroyer l'abonnement, veuiller réessayer!",
                type:              'error',
            ));

            return null;
        }


        try {
            
            return DB::transaction(function () use ($tenant, $plan, $daysCount) {
                
                $subscription = Subscription::create([
                    'tenant_id' => $tenant->id,
                    'plan_id' => $plan->id,
                    'subscription_request_id' => null,
                    'started_at' => now(),
                    'expire_at' => now()->addDays($daysCount),
                    'status' => 'active',
                    'is_free' => true,
                ]);

                $moduleAccess = TenantModuleAccess::firstOrCreate(['tenant_id' => $tenant->id]);
                $moduleAccess->applyPack($plan->pack);
                $moduleAccess->update(['pack_expires_at' => $subscription->expire_at]);

                DB::afterCommit(function () use ($tenant, $plan, $subscription, $daysCount) {
                    
                    $central  = CentralUser::first();

                    $central?->notify(new CentralRealTimeNotification(
                        title:             "ABONNEMENT OFFERT",
                        message:           "Vous avez offert un abonnement {$plan->name} à {$tenant->getFullName()} pour une durée de {$daysCount}",
                        type:              'success',
                    ));
                    
                });

                return $subscription;
            });
            
        } catch (\Throwable $th) {
            
            $central  = CentralUser::first();

            $central?->notify(new CentralRealTimeNotification(
                title:             "ECHEC DE L'OCTROIE DE L'ABONNEMENT",
                message:           "L'octroie de l'abonnement a échoué! : " . cutter($th->getMessage(), 2000),
                type:              'error',
            ));

            return null;
        }
        finally{

            broadcast(new CentralDataUpdatedEvent());

            broadcast(new TenantDirectorDataUpdatedEvent($tenantId));

        }
    }
}