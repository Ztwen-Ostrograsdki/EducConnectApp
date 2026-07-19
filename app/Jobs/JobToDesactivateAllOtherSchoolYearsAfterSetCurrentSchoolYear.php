<?php

namespace App\Jobs;

use App\Events\DataUpdatedEvent;
use App\Models\SchoolYear;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JobToDesactivateAllOtherSchoolYearsAfterSetCurrentSchoolYear implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   public function __construct(
        public string $tenantId,
        public readonly string $schoolYearSlug,
    ) {}

    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        try {

            tenancy()->initialize($this->tenantId);

            $director = User::first();

            if(!$director){

                $this->fail("COMPTE DIRECTEUR INEXISTANT : IMPOSSIBLE DE FAIRE UNE QUELCONQUE ACTION AVANT LE COMPTE DIRECTEUR");

                return;

            }

            $schoolYear = SchoolYear::firstWhere('slug', $this->schoolYearSlug);

            if($schoolYear && $schoolYear->is_active){

                SchoolYear::where('slug', '<>', $this->schoolYearSlug)->where('is_active', true)->update(['is_active' => false]);
                
            }
            else{

                $error_message = "L'année scolaire {$this->schoolYearSlug} introuvable ou non active!";

                $director?->notify(new RealTimeNotification(
                    userEmail: $director?->email,
                    tenantId: $this->tenantId,
                    title:             "Erreur de mise à jour des données actives ",
                    message:           $error_message,
                    type:              'error',
                ));

                $this->fail($error_message);
            }

        } catch (\Throwable $th) {

            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $this->tenantId,
                title:     "Erreur de mise à jour des données actives  ",
                message:   cutter($th->getMessage(), 1500),
                type:      'error',
            ));
        }
        finally{

            broadcast(new DataUpdatedEvent($this->tenantId));

            tenancy()->end();
        }
    }

}
