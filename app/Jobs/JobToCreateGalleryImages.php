<?php

namespace App\Jobs;

use App\Events\DataUpdatedEvent;
use App\Helpers\Support\TenantStorage;
use App\Models\Gallery;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class JobToCreateGalleryImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  array<int, array{path: string, title: ?string, description: ?string}>  $images
     */
    public function __construct(
        public string $tenantId,
        public array $images,
        public ?int $creatorId = null,
    ) {}

    public function handle(): void
    {
        tenancy()->initialize($this->tenantId);

        $created = 0;
        $errors  = [];

        $director = User::query()->first();

        try {
            foreach ($this->images as $data) {
                try {
                    Gallery::create([
                        'uuid'        => (string) Str::uuid(),
                        'path'        => $data['path'],
                        'creator'     => $this->creatorId,
                        'title'       => $data['title'] ?? null,
                        'description' => $data['description'] ?? null,
                        'hidden'      => false,
                    ]);

                    $created++;
                } catch (\Throwable $e) {
                    $errors[] = ($data['title'] ?? 'Image') . ' : ' . $e->getMessage();

                    // On tente de supprimer le fichier déjà stocké pour éviter les orphelins
                    try {
                        if (! empty($data['path'])) {
                            TenantStorage::delete($data['path']);
                        }
                    } catch (\Throwable) {
                        // silencieux
                    }

                    $director?->notify(new RealTimeNotification(
                        userEmail: $director->email,
                        tenantId:  $this->tenantId,
                        title:     "ÉCHEC CRÉATION IMAGE GALERIE",
                        message:   cutter($e->getMessage(), 2000),
                        type:      'error',
                    ));
                }
            }
        } catch (\Throwable $th) {
            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $this->tenantId,
                title:     "ERREUR LORS DE LA CRÉATION DES IMAGES GALERIE",
                message:   cutter($th->getMessage(), 2000),
                type:      'error',
            ));
        } finally {
            broadcast(new DataUpdatedEvent($this->tenantId));
            tenancy()->end();
        }

        $director?->notify(new RealTimeNotification(
            userEmail: $director->email,
            tenantId:  $this->tenantId,
            title:     "CRÉATION DES IMAGES GALERIE TERMINÉE",
            message:   "{$created} succès - " . count($errors) . " erreur(s).",
            type:      $created > 0 ? 'success' : 'error',
        ));
    }
}
