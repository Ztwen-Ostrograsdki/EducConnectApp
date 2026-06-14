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
     * @param string $tenantId  ID du tenant
     */
    public static function outputPath(
        string $category,
        string $filename,
    ): string {

        $folder = self::CATEGORY_MAP[$category] ?? 'documents';

        $slug   = Str::slug($filename) . '_' . now()->format('Ymd_His');

        // TenantStorage::ensureDirectory retourne le chemin relatif
        // ex: tenants/mon-lycee/documents
        $relativePath = TenantStorage::ensureDirectory($folder);

        // On construit le chemin absolu depuis le disk public
		return Storage::disk('public')->path("{$relativePath}/{$slug}.pdf");

    }

    /**
     * Résout l'URL publique via TenantStorage::url().
     *
     * @param string $outputPath Chemin absolu retourné par outputPath()
     */
    public static function resolvePublicUrl(string $outputPath): string
    {
        // Extrait le chemin relatif depuis storage/app/public/
        $relative = Str::after($outputPath, storage_path('app/public/'));

        return Storage::url($relative);
    }

    /**
     * Dispatch le job GeneratePdf avec les options par défaut.
     *
     * @param string               $view
     * @param array<string, mixed> $data
     * @param string               $outputPath   Depuis PdfFactory::outputPath()
     * @param array<string, mixed> $overrides    Options Browsershot à surcharger
     * @param string|null          $tenantId
     * @param string|null          $notifiable   FQCN du modèle notifiable
     * @param int|null             $notifiableId
     * @param string|null          $notification FQCN de la notification
     */
    public static function dispatch(
        string  $view,
        array   $data,
        string  $outputPath,
        array   $overrides    = [],
        ?string $tenantId     = null,
        ?string $notifiable   = null,
        ?int    $notifiableId = null,
        ?string $notification = null,
    ): void {
        $options = array_merge([
            'format'    => 'A4',
            'landscape' => false,
            'margins'   => [14, 12, 16, 12],
        ], $overrides);

        JobToGeneratePdfFromView::dispatch(
            view:           $view,
            data:           $data,
            outputPath:     $outputPath,
            options:        $options,
            tenantId:       $tenantId,
            notifiable:     $notifiable,
            notifiableId:   $notifiableId,
            notification:   $notification,
        )->onQueue('pdf');
    }
}