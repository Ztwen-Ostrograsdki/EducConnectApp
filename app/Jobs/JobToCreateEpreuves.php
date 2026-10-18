<?php

namespace App\Jobs;

use App\Events\DataUpdatedEvent;
use App\Helpers\Support\TenantStorage;
use App\Models\Epreuve;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class JobToCreateEpreuves implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  string  $tenantId
     * @param  array   $filesData   Tableau de fichiers temporaires avec métadonnées
     *                              chaque item : [
     *                                'tmp_path'      => string (chemin temporaire),
     *                                'original_name' => string,
     *                                'mime'          => string|null,
     *                                'size'          => int|null,
     *                              ]
     * @param  array   $context     Données communes (classe, filière, period, type, etc.)
     * @param  int     $authorId
     * @param  string  $teacherName
     * @param  int     $lot
     */
    public function __construct(
        public string $tenantId,
        public array $filesData,
        public array $context,
        public int $authorId,
        public string $teacherName,
        public int $lot,
    ) {}

    public function handle(): void
    {
        tenancy()->initialize($this->tenantId);

        $created = 0;
        $errors  = [];
        $total   = count($this->filesData);

        $director = User::first();
        $author   = User::find($this->authorId);

        try {
            foreach ($this->filesData as $fileData) {
                $tmpPath      = $fileData['tmp_path'] ?? null;
                $originalName = $fileData['original_name'] ?? 'epreuve';
                $storedPath   = null;

                try {
                    if (! $tmpPath || ! File::exists($tmpPath)) {
                        $errors[] = $originalName . ' : fichier temporaire introuvable.';
                        continue;
                    }

                    // Nom de fichier (filename) = nom original slugifié (sans extension pour le slug, on garde l'extension)
                    $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                    $baseName  = pathinfo($originalName, PATHINFO_FILENAME);
                    $slugName  = Str::slug($baseName);

                    if ($slugName === '') {
                        $slugName = 'epreuve-' . Str::random(6);
                    }

                    $filename = $extension
                        ? $slugName . '.' . strtolower($extension)
                        : $slugName;

                    // Stockage via TenantStorage (même logique que UpdateProfilePhoto).
                    // On recrée un UploadedFile à partir du chemin temporaire pour
                    // rester compatible avec la signature attendue par TenantStorage.
                    $uploaded = new \Illuminate\Http\UploadedFile(
                        $tmpPath,
                        $originalName,
                        $fileData['mime'] ?? null,
                        null,
                        true // test mode → pas de is_uploaded_file()
                    );

                    $storedPath = TenantStorage::store($uploaded, 'epreuves');

                    // Création du modèle dans un try/catch dédié :
                    // si l'enregistrement échoue, on supprime le fichier stocké
                    try {
                        Epreuve::create([
                            'uuid'                  => (string) Str::uuid(),
                            'filename'              => $filename,
                            'path'                  => $storedPath,
                            'classe_name'           => $this->context['classe_name'] ?? null,
                            'promotion_id'          => $this->context['promotion_id'] ?? null,
                            'filiar_id'             => $this->context['filiar_id'] ?? null,
                            'classe_id'             => $this->context['classe_id'] ?? null,
                            'serial_id'             => $this->context['serial_id'] ?? null,
                            'subject_id'            => $this->context['subject_id'] ?? null,
                            'school_year_id'        => $this->context['school_year_id'],
                            'teacher_name'          => $this->teacherName,
                            'author_id'             => $this->authorId,
                            'period'                => $this->context['period'],
                            'period_as_label'       => $this->context['period_as_label'] ?? null,
                            'type'                  => $this->context['type'] ?? 'devoir',
                            'downloaded'            => false,
                            'downloaded_count'      => 0,
                            'lot'                   => $this->lot,
                            'downloadable_by_others'=> false,
                            'school_year_slug'      => $this->context['school_year_slug'] ?? null,
                            'published'             => false,
                        ]);

                        $created++;
                    } catch (\Throwable $e) {
                        // Échec de l'enregistrement → on ne laisse pas le fichier orphelin
                        if ($storedPath) {
                            TenantStorage::delete($storedPath);
                        }

                        $errors[] = $originalName . ' : ' . $e->getMessage();

                        $director?->notify(new RealTimeNotification(
                            userEmail: $director->email,
                            tenantId:  $this->tenantId,
                            title:     'ÉCHEC ENREGISTREMENT ÉPREUVE — ' . $originalName,
                            message:   cutter($e->getMessage(), 2000),
                            type:      'error',
                        ));
                    }
                } catch (\Throwable $e) {
                    // Échec avant ou pendant le stockage
                    if ($storedPath) {
                        try {
                            TenantStorage::delete($storedPath);
                        } catch (\Throwable) {
                            // ignore
                        }
                    }

                    $errors[] = $originalName . ' : ' . $e->getMessage();

                    $director?->notify(new RealTimeNotification(
                        userEmail: $director->email,
                        tenantId:  $this->tenantId,
                        title:     'ÉCHEC TRAITEMENT ÉPREUVE — ' . $originalName,
                        message:   cutter($e->getMessage(), 2000),
                        type:      'error',
                    ));
                } finally {
                    // Nettoyage du fichier temporaire
                    if ($tmpPath && File::exists($tmpPath)) {
                        @unlink($tmpPath);
                    }
                }
            }
        } catch (\Throwable $th) {
            $director?->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId:  $this->tenantId,
                title:     'ERREUR GLOBALE LORS DE L\'ENVOI DES ÉPREUVES',
                message:   cutter($th->getMessage(), 2000),
                type:      'error',
            ));
        } finally {
            broadcast(new DataUpdatedEvent($this->tenantId));
            tenancy()->end();
        }

        $failedCount = count($errors);

        // Notification au directeur
        $director?->notify(new RealTimeNotification(
            userEmail: $director->email,
            tenantId:  $this->tenantId,
            title:     'Envoi d\'épreuves par ' . $this->teacherName,
            message:   $this->teacherName . ' a envoyé ' . $total . ' épreuve(s). Résultat : ' . $created . ' succès, ' . $failedCount . ' échec(s).',
            type:      $failedCount > 0 ? 'warning' : 'success',
        ));

        // Notification à l'enseignant (auteur)
        if ($author) {
            $author->notify(new RealTimeNotification(
                userEmail: $author->email,
                tenantId:  $this->tenantId,
                title:     'Résultat de votre envoi d\'épreuves',
                message:   'Traitement terminé : ' . $created . ' succès, ' . $failedCount . ' échec(s) sur ' . $total . ' fichier(s).',
                type:      $failedCount > 0 ? 'warning' : 'success',
            ));
        }
    }
}
