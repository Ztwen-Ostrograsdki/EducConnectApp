<?php

namespace App\Jobs;

use App\Events\DataUpdatedEvent;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


#[Timeout(300)]
class JobBulkerActionsOnModels implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Nombre de tentatives max (retry manuel via bouton).
     */
    public int $tries = 2;


    protected array $allowedsMethods = [
        'delete',
        'forceDelete',
        'create',
        'firstOrCreate',
        'update',
        'restore',
    ];

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string   $tenantId,
        public string   $model,
        public array    $ids,
        public string   $method,
        public ?array   $options = [],
        public bool     $withTrashedDeleted = true,
        public ?string  $taskTitle = "ACTION EN MASSE SUR LA BASE DE DONNEES",
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            $this->assetMethodIsValid();

            tenancy()->initialize($this->tenantId);

            $tenant = tenancy()->tenant;

            $director = User::first();

            if(!$director){

                $this->fail("COMPTE DIRECTEUR INEXISTANT : IMPOSSIBLE D'EXECUTER TOUTE ACTION SUR LA BASE DE DONNEES AVANT LE COMPTE DIRECTEUR");

                return;
            }

            if(!$this->withTrashedDeleted){

                $query = $this->model::whereIn('id', $this->ids);
            }
            else{

                $query = $this->model::withTrashed()->whereIn('id', $this->ids);
            }

            if(empty($this->options)){

                $query->{$this->method}();

            }
            else{

                $query->{$this->method}($this->options);
            }

            broadcast(new DataUpdatedEvent($this->tenantId));

            $director?->notify(new RealTimeNotification(
                userEmail: $director?->email,
                tenantId: $this->tenantId,
                title:             $this->taskTitle . " TERMINEE",
                message:           $this->taskTitle . " TERMINEE. " . '(' . count($this->ids) . ') tâche(s) exécutée(s)!',
                type:              'success',
            ));

        } catch (\Throwable $th) {

            $director?->notify(new RealTimeNotification(
                userEmail: $director?->email,
                tenantId: $this->tenantId,
                title:             "ECHEC DE L'EXECUTION " . $this->taskTitle,
                message:           cutter($th->getMessage(), 200),
                type:              'error',
            ));

            $this->fail($th->getMessage());
            return;

        }
        finally{

            tenancy()->end();
        }
    }



    public function assetMethodIsValid()
    {
        if(!in_array($this->method, $this->allowedsMethods, true)){

            tenancy()->initialize($this->tenantId);

            $director = User::first();

            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $this->tenantId,
                title:     "Méthode ou action invalide !",
                message:   "Méthode ou action invalide !",
                type:      'error',
            ));

            $this->fail("Méthode ou action invalide !");

            return;

        }
    }
}
