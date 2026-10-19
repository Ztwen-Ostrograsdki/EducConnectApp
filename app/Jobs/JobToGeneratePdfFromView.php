<?php

namespace App\Jobs;

use App\Events\AnyErrorEvent;
use App\Events\DataUpdatedEvent;
use App\Jobs\JobToShareDocumentToSomeone;
use App\Models\GeneratedDocument;
use App\Models\SchoolYear;
use App\Models\User;
use App\Notifications\PDFIsReady;
use App\Services\PDFFactory;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Spatie\Browsershot\Browsershot;

#[Timeout(300)]
class JobToGeneratePdfFromView implements ShouldQueue
{
    use Queueable, Batchable, Dispatchable, InteractsWithQueue, SerializesModels;

    public int $tries = 2;

    /**
     * @param  string                $view         Nom de la vue Blade à rendre
     * @param  array<string, mixed>  $data         Données passées à la vue
     * @param  string                $outputPath   Chemin relatif du fichier PDF (disk public)
     * @param  array<string, mixed>  $options      Options Browsershot (format, orientation, margins…)
     * @param  string|null           $tenantId     Si non null, initialise le contexte tenant avant rendu
     * @param  string|null           $notifiable   FQCN du modèle notifiable (ex: App\Models\User)
     * @param  int|null              $notifiableId ID du modèle à notifier
     * @param  string|null           $notification FQCN de la notification à envoyer
     */
    public function __construct(
        public readonly string $view,
        public readonly array $data,
        public readonly string $outputPath,
        public readonly array $options = [],
        public readonly ?string $tenantId = null,
        public readonly ?int $notifiableId = null,
        public readonly ?string $notifiable = null,
        public readonly ?string $notification = null,
        public readonly ?array $docDBInfos = null,
        public readonly bool $paginable = true,
        public readonly ?array $receiverIds = null,
        public readonly bool $repeatHeader = false,
    ) {}

    public function handle(): void
    {
        if ($this->tenantId) {
            tenancy()->initialize($this->tenantId);
        }

        try {
            $html = view($this->view, $this->data)->render();

            // Garantit un document HTML UTF-8 complet pour Browsershot / Chromium
            // (évite le mojibake type "PRÃNOM", "08h00 Ã 10h00")
            $html = $this->ensureUtf8HtmlDocument($html);

            Carbon::setLocale('fr');
            $now  = Carbon::now('Africa/Abidjan');
            $name = env('APP_NAME');

            $formattedDate = 'Générée et imprimée avec le logitiel web ' . $name . ' le '
                . $now->isoFormat('dddd D MMMM YYYY')
                . ' à '
                . $now->isoFormat('HH\H mm\min ss\s');

            $header_title = $this->data['pdf_title'] ?? 'Document ' . ' Généré et imprimé avec le logitiel web ' . $name;

            $banner = ($this->repeatHeader && $this->tenantId)
                ? tenant()?->header_image_data_uri
                : null;

            if ($this->paginable) {
                $footerHtml = '<div style="font-size:10px; width:100%; text-align:center; color:black;">'
                    . $header_title . ' | '
                    . $formattedDate
                    . ' | Page <span class="pageNumber"></span> / <span class="totalPages"></span>'
                    . '</div>';
            } else {
                $footerHtml = '<div style="font-size:10px; width:100%; text-align:center; color:black;">'
                    . $formattedDate
                    . '</div>';
            }

            // Header vide par défaut : évite date + titre "Document" de Chromium
            $emptyHeaderHtml = '<div style="font-size:0;height:0;margin:0;padding:0;line-height:0;overflow:hidden;"></div>';

            $browsershot = Browsershot::html($html)
                ->setNodeBinary(config('browsershot.node_binary'))
                ->setNpmBinary(config('browsershot.npm_binary'))
                ->setChromePath(config('browsershot.chrome_binary'))
                ->timeout(config('browsershot.timeout'))
                ->addChromiumArguments(config('browsershot.chromium_args'))
                ->ignoreHttpsErrors()
                ->showBackground()
                ->emulateMedia('screen')
                ->margins(15, 15, 15, 15)
                ->showBrowserHeaderAndFooter()
                ->headerHtml($emptyHeaderHtml)
                ->footerHtml($footerHtml)
                ->waitUntilNetworkIdle();

            if (config('browsershot.no_sandbox')) {
                $browsershot->noSandbox();
            }

            if ($this->repeatHeader && $banner) {
                $bannerHeightMm = 28; // hauteur réservée au bandeau

                $browsershot
                    ->headerHtml(
                        '<style>#header, #footer { padding: 0 !important; margin: 0 !important; }</style>'
                        . '<div style="width:100%;margin:0;padding:0;font-size:0;line-height:0;-webkit-print-color-adjust:exact;print-color-adjust:exact;">'
                        . '<img src="' . $banner . '" style="display:block;width:100%;height:auto;max-height:' . $bannerHeightMm . 'mm;margin:0;padding:0;border:0;">'
                        . '</div>'
                    )
                    // Haut = bandeau + 2mm (réduit l'espace inutile avant l'image / le contenu)
                    ->margins($bannerHeightMm + 2, 10, 10, 10, 'mm');
            }

            $this->applyOptions($browsershot);

            // Écriture disque : chemin absolu ; stockage DB : chemin relatif ($this->outputPath)
            $absolutePath = PDFFactory::absolutePath($this->outputPath);

            $dir = dirname($absolutePath);
            if (! File::isDirectory($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            $browsershot->save($absolutePath);

            $this->robotNotifyer();
        } catch (\Throwable $th) {
            broadcast(new AnyErrorEvent('error', $th->getMessage(), $this->tenantId, $this->notifiableId));
            $this->fail($th->getMessage());

            return;
        } finally {
            if ($this->tenantId) {
                tenancy()->end();
            }
        }
    }

    /**
     * Enveloppe le HTML rendu dans un document complet avec charset UTF-8
     * si ce n’est pas déjà le cas (les vues Livewire renvoient souvent un fragment).
     */
    private function ensureUtf8HtmlDocument(string $html): string
    {
        $trimmed = ltrim($html);

        // Déjà un document HTML avec charset déclaré
        if (
            str_starts_with(strtolower($trimmed), '<!doctype')
            || str_starts_with(strtolower($trimmed), '<html')
        ) {
            if (! preg_match('/<meta[^>]+charset\s*=/i', $html)) {
                $html = preg_replace(
                    '/<head([^>]*)>/i',
                    '<head$1><meta charset="UTF-8"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">',
                    $html,
                    1
                ) ?? $html;
            }

            return $html;
        }

        return '<!DOCTYPE html>'
            . '<html lang="fr">'
            . '<head>'
            . '<meta charset="UTF-8">'
            . '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'
            . '<title>Document</title>'
            . '</head>'
            . '<body>' . $html . '</body>'
            . '</html>';
    }

    /**
     * Applique les options Browsershot de manière dynamique.
     * Chaque clé du tableau $options correspond à une méthode de Browsershot.
     *
     * Exemples d'options supportées :
     *   'format'      => 'A4'
     *   'landscape'   => true
     *   'margins'     => [14, 12, 16, 12]   // top, right, bottom, left
     *   'scale'       => 1.0
     *   'noSandbox'   => true
     *   'timeout'     => 60000
     */
    private function applyOptions(Browsershot $browsershot): void
    {
        $map = [
            'format'    => fn ($v) => $browsershot->format($v),
            'landscape' => fn ($v) => $v ? $browsershot->landscape() : null,
            'portrait'  => fn ($v) => $v ? $browsershot->portrait() : null,
            // 'margins'   => fn ($v) => $browsershot->margins(...$v),
            'scale'     => fn ($v) => $browsershot->scale($v),
            'timeout'   => fn ($v) => $browsershot->timeout($v),
            'width'     => fn ($v) => $browsershot->windowSize($v, $this->options['height'] ?? 800),
            'userAgent' => fn ($v) => $browsershot->userAgent($v),
            'delay'     => fn ($v) => $browsershot->setDelay($v),
        ];

        foreach ($this->options as $key => $value) {
            if (isset($map[$key])) {
                ($map[$key])($value);
            }
        }
    }

    /**
     * Envoie une notification au modèle cible si les paramètres sont fournis.
     */
    private function robotNotifyer(): void
    {
        if (! $this->notifiableId) {
            return;
        }

        $user = User::find($this->notifiableId);

        $activeYear = SchoolYear::current()?->first();

        $url = $this->resolvePublicUrl();

        // Chemin relatif en base (cohérent avec TenantStorage / téléchargements)
        $relativePath = $this->outputPath;
        $absolutePath = PDFFactory::absolutePath($relativePath);

        $docDBInfos = $this->docDBInfos;

        if ($docDBInfos) {
            GeneratedDocument::create([
                'type'                   => $this->options['document_type'] ?? 'document',
                'filename'               => basename($relativePath),
                'school_year_id'         => $activeYear?->id ?? null,
                'school_year_slug'       => $activeYear?->slug ?? null,
                'path'                   => $relativePath,
                'url'                    => $url,
                'user_id'                => $this->notifiableId,
                'classe_id'              => $docDBInfos['classe_id'] ?? null,
                'filiar_id'              => $docDBInfos['filiar_id'] ?? null,
                'for_student_id'         => $docDBInfos['for_student_id'] ?? null,
                'period'                 => $docDBInfos['period'] ?? null,
                'for_parent_id'          => $docDBInfos['for_parent_id'] ?? null,
                'for_parents'            => $docDBInfos['for_parents'] ?? false,
                'serial_id'              => $docDBInfos['serial_id'] ?? null,
                'for_teacher_id'         => $docDBInfos['for_teacher_id'] ?? null,
                'for_teachers'           => $docDBInfos['for_teachers'] ?? false,
                'promotion_id'           => $docDBInfos['promotion_id'] ?? null,
                'promotionsGrouped'      => $docDBInfos['promotionsGrouped'] ?? null,
                'tenant_id'              => $this->tenantId,
                'downloadable_by_others' => $this->options['downloadable_by_others'] ?? false,
            ]);
        } else {
            GeneratedDocument::create([
                'type'                   => $this->options['document_type'] ?? 'document',
                'filename'               => basename($relativePath),
                'path'                   => $relativePath,
                'url'                    => $url,
                'user_id'                => $this->notifiableId,
                'tenant_id'              => $this->tenantId,
                'school_year_id'         => $activeYear?->id ?? null,
                'school_year_slug'       => $activeYear?->slug ?? null,
                'downloadable_by_others' => $this->options['downloadable_by_others'] ?? false,
            ]);
        }

        $user?->notify(new PDFIsReady(
            title: 'PDF EST PRÊT',
            message: 'Votre document a été bien généré et est à présent prêt!',
            type: 'success',
            url: $url,
            tenantId: $this->tenantId,
            userEmail: $user->email,
            target: $this->data['target'],
            eventName: $this->data['eventName'],
        ));

        if ($this->receiverIds) {
            $title = $this->docDBInfos['title_for_sendable_document']
                ?? basename($relativePath);

            $space_url = $this->docDBInfos['space_url'] ?? null;

            // Partage : chemin absolu pour lecture fichier côté job destinataire
            JobToShareDocumentToSomeone::dispatch(
                tenantId: $this->tenantId,
                file_path: $absolutePath,
                receiverIds: $this->receiverIds,
                title: $title,
                space_url: $space_url,
            );
        }

        broadcast(new DataUpdatedEvent($this->tenantId));
    }

    /**
     * Résout l'URL publique à partir du chemin de stockage (relatif ou absolu).
     */
    private function resolvePublicUrl(): string
    {
        return PDFFactory::resolvePublicUrl($this->outputPath);
    }
}
