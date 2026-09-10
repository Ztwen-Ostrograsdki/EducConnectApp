<?php

namespace App\Jobs;

use App\Events\DataUpdatedEvent;
use App\Models\Personnel;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class JobToCreatePersonnels implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $tenantId,
        public array $personnels,
        public int $schoolYearId,
    ) {}

    public function handle(): void
    {
        // Initialise le tenant (stancl/tenancy ou équivalent)
        tenancy()->initialize($this->tenantId);

        $created = 0;
        $errors  = [];

        $director = User::first();

        try {
            foreach ($this->personnels as $data) {
                try {

                    $full_name = $data['name'] . ' ' .  $data['prenames'];
                    
                    // Évite les doublons nom + prénoms
                    $exists = Personnel::query()
                        ->where('name', $data['name'] ?? '')
                        ->where('prenames', $data['prenames'] ?? '')
                        ->exists();

                    if ($exists) {
                        $errors[] = ($data['name'] ?? '') . ' ' . ($data['prenames'] ?? '') . ' : déjà existant.';
                        continue;
                    }

                    Personnel::create([
                        'uuid'           => $data['uuid'] ?? (string) Str::uuid(),
                        'name'           => $data['name'] ?? '',
                        'prenames'       => $data['prenames'] ?? '',
                        'birth_date'     => $data['birth_date'] ?? null,
                        'contacts'       => $data['contacts'] ?? null,
                        'title'          => $data['title'] ?? null,
                        'description'    => $data['description'] ?? null,
                        'school_year_id' => $this->schoolYearId,
                        'is_active'      => true,
                        'hidden'         => false,
                        'gender'         => $data['gender'] ?? null,
                        'grade'          => $data['grade'] ?? null,
                        'since'          => $data['since'] ?? null,
                        'ended_at'       => null,
                    ]);

                    $created++;
                    
                } catch (\Throwable $e) {

                    $full_name = $data['name'] . ' ' .  $data['prenames'];

                    $errors[] = ($data['name'] ?? 'Inconnu') . ' : ' . $e->getMessage();

                    $director?->notify(new RealTimeNotification(
                        userEmail: $director->email,
                        tenantId:  $this->tenantId,
                        title:     "ECHEC CRÉATION DU PERSONNEL " . $full_name ?? '',
                        message:   cutter($e->getMessage(), 2000),
                        type:      'error',
                    ));
                    
                }
            }
        } catch (\Throwable $th) {
            
            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $this->tenantId,
                title:     "ERREUR PRODUITE LORS DE LA CREATION DES PERSONNELS",
                message:   cutter($th->getMessage(), 2000),
                type:      'error',
            ));
        }
        finally{

            broadcast(new DataUpdatedEvent($this->tenantId));

            tenancy()->end();
        }

        $director?->notify(new RealTimeNotification(
            userEmail: $director->email,
            tenantId:  $this->tenantId,
            title:     "ERREUR PRODUITE LORS DE LA CREATION DES PERSONNELS",
            message:   "CREATION DES PERSONNELS TERMINEE. Détails : " . $created . " succès - " . count($errors) . " erreurs.",
            type:      'success',
        ));
    }
}
