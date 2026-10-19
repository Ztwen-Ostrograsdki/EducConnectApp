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
        'marks'         => 'notes',
        'classes'       => 'documents',
        'temp'          => 'temp',
    ];

    /**
     * Résout le chemin relatif de sortie dans le dossier tenant existant.
     * À stocker en base (comme TenantStorage::store).
     *
     * Exemple : tenants/{tenantId}/documents/mon-fichier_20261018_123045.pdf
     *
     * @param  string  $category  Catégorie logique (teachers, bulletins, convocations…)
     * @param  string  $filename  Nom du fichier sans extension ni timestamp
     */
    public static function outputPath(
        string $category,
        string $filename,
    ): string {
        $folder = self::CATEGORY_MAP[$category] ?? 'documents';

        $slug = Str::slug($filename) . '_' . now()->format('Ymd_His') . '.pdf';

        // Dossier relatif tenant (ex: tenants/xxx/documents)
        $relativeDir = TenantStorage::ensureDirectory($folder);

        // Chemin relatif complet — pas d'absolu
        return ltrim("{$relativeDir}/{$slug}", '/');
    }

    /**
     * Convertit un chemin relatif (ou absolu pour rétrocompat) en chemin absolu disque.
     * À utiliser côté job / Browsershot pour l'écriture du fichier.
     */
    public static function absolutePath(string $path): string
    {
        $path = str_replace('\\', '/', $path);

        if (str_starts_with($path, '/') || preg_match('/^[A-Za-z]:\//', $path)) {
            return $path;
        }

        return Storage::disk('public')->path($path);
    }

    /**
     * Résout l'URL publique.
     *
     * @param  string  $outputPath  Chemin relatif (préféré) ou absolu
     */
    public static function resolvePublicUrl(string $outputPath): string
    {
        $outputPath = str_replace('\\', '/', $outputPath);

        // Déjà relatif
        if (! str_starts_with($outputPath, '/') && ! preg_match('/^[A-Za-z]:\//', $outputPath)) {
            return Storage::url(ltrim($outputPath, '/'));
        }

        // Ancien format absolu → extraction du relatif
        $storagePath = str_replace('\\', '/', Storage::disk('public')->path(''));
        $relative    = ltrim(Str::after($outputPath, $storagePath), '/');

        return Storage::url($relative);
    }

    /**
     * Dispatch le job GeneratePdf avec les options par défaut.
     *
     * @param  string                $view
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $overrides     Options Browsershot à surcharger
     * @param  string|null           $tenantId
     * @param  string|null           $notifiable    FQCN du modèle notifiable
     * @param  int|null              $notifiableId
     * @param  string|null           $notification  FQCN de la notification
     */
    public static function dispatch(
        string $view,
        array $data,
        string $filename,
        string $category = 'documents',
        string $documentType = 'document',
        bool $downloadableByOthers = false,
        array $overrides = [],
        ?string $tenantId = null,
        ?string $notifiable = null,
        ?int $notifiableId = null,
        ?string $notification = null,
        ?array $docDBInfos = null,
        bool $paginable = true,
        ?array $receiverIds = null,
        bool $repeatHeader = false,
    ): void {
        $folder     = self::CATEGORY_MAP[$category] ?? 'documents';
        $outputPath = self::outputPath($category, $filename); // relatif

        $options = array_merge(
            [
                'format'                 => 'A4',
                'landscape'              => false,
                'margins'                => [14, 12, 16, 12],
                'folder'                 => $folder,
                'document_type'          => $documentType,
                'downloadable_by_others' => $downloadableByOthers,
            ],
            $overrides
        );

        JobToGeneratePdfFromView::dispatch(
            view: $view,
            data: $data,
            outputPath: $outputPath,
            options: $options,
            tenantId: $tenantId,
            notifiableId: $notifiableId,
            notifiable: $notifiable,
            notification: $notification,
            docDBInfos: $docDBInfos,
            paginable: $paginable,
            receiverIds: $receiverIds,
            repeatHeader: $repeatHeader
        )->onQueue('pdf');
    }
}
