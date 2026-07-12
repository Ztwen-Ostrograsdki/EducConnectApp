<?php

namespace App\Services;


use App\Helpers\Support\TenantStorage;
use App\Jobs\JobToGeneratePdfFromView;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PDFFactory
{
    /**
     * Catégories mappées sur les dossiers tenant existants.
     *
     * Structure déjà créée à l'event TenantCreated :
     * storage/app/public/tenants/{tenantId}/
     *   ├── profiles/
     *   ├── documents/   ← listes, rapports, convocations…
     *   ├── bulletins/   ← bulletins de notes
     *   ├── epreuves/    ← sujets, corrigés…
     *   ├── schools/     ← logos, tampons…
     *   └── temp/        ← fichiers temporaires
     */
    private const CATEGORY_MAP = [
        'teachers'      => 'documents',
        'convocations'  => 'documents',
        'rapports'      => 'documents',
        'listes'        => 'documents',
        'bulletins'     => 'bulletins',
        'epreuves'      => 'epreuves',
        'temp'          => 'temp',
    ];

    /**
     * Résout le chemin absolu de sortie dans le dossier tenant existant.
     *
     * @param string $category  Catégorie logique (teachers, bulletins, convocations…)
     * @param string $filename  Nom du fichier sans extension ni timestamp
     */

    public static function outputPath(
        string $category,
        string $filename,
    ): string {

        $folder   = self::CATEGORY_MAP[$category] ?? 'documents';

        $slug     = Str::slug($filename) . '_' . now()->format('Ymd_His') . '.pdf';

        // Chemin relatif via TenantStorage
        $relativePath = TenantStorage::ensureDirectory($folder);

        // Chemin absolu via Storage::disk('public')->path()
        // Pas de storage_path() — il est affecté par StorageTenancyBootstrapper
        return Storage::disk('public')->path("{$relativePath}/{$slug}");
    }

    /**
     * Résout l'URL publique via TenantStorage::url().
     *
     * @param string $outputPath Chemin absolu retourné par outputPath()
     */
    public static function resolvePublicUrl(string $outputPath): string
    {
        // Normalise les backslashes Windows en slashes
        $outputPath  = str_replace('\\', '/', $outputPath);
        $storagePath = str_replace('\\', '/', Storage::disk('public')->path(''));

        // Extrait le chemin relatif depuis la racine du disk public
        $relative = ltrim(Str::after($outputPath, $storagePath), '/');

        return Storage::url($relative);
    }

    /**
     * Dispatch le job GeneratePdf avec les options par défaut.
     *
     * @param string               $view
     * @param array<string, mixed> $data
     * @param array<string, mixed> $overrides    Options Browsershot à surcharger
     * @param string|null          $tenantId
     * @param string|null          $notifiable   FQCN du modèle notifiable
     * @param int|null             $notifiableId
     * @param string|null          $notification FQCN de la notification
     */
    public static function dispatch(
        string  $view,
        array   $data,
        string  $filename,
        string  $category             = 'documents',
        string  $documentType         = 'document',
        bool    $downloadableByOthers = false,
        array   $overrides            = [],
        ?string $tenantId             = null,
        ?string $notifiable           = null,
        ?int    $notifiableId         = null,
        ?string $notification         = null,
    ): void {

        $folder       = self::CATEGORY_MAP[$category] ?? 'documents';

        $outputPath   = self::outputPath($category, $filename);

        $options = array_merge(
            [
                'format'                  => 'A4',
                'landscape'               => false,
                'margins'                 => [14, 12, 16, 12],
                'folder'                  => $folder,
                'document_type'           => $documentType,
                'downloadable_by_others'  => $downloadableByOthers,
            ], $overrides
        );

        JobToGeneratePdfFromView::dispatch(
            view:           $view,
            data:           $data,
            outputPath:     $outputPath,
            options:        $options,
            tenantId:       $tenantId,
            notifiableId:   $notifiableId,
            notifiable:     $notifiable,
            notification:   $notification,
        )->onQueue('pdf');
    }
}