<?php

namespace App\Jobs;

use App\Events\AnyErrorEvent;
use App\Models\GeneratedDocument;
use App\Models\User;
use App\Notifications\PDFIsReady;
use App\Services\PDFFactory;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Spatie\Browsershot\Browsershot;

class JobToGeneratePdfFromView implements ShouldQueue
{
    use Queueable, Batchable, Dispatchable, InteractsWithQueue, SerializesModels;

    public int $timeout = 120;
    public int $tries   = 2;

    /**
     * @param string               $view       Nom de la vue Blade à rendre
     * @param array<string, mixed> $data       Données passées à la vue
     * @param string               $outputPath Chemin absolu du fichier PDF généré
     * @param array<string, mixed> $options    Options Browsershot (format, orientation, margins…)
     * @param string|null          $tenantId   Si non null, initialise le contexte tenant avant rendu
     * @param string|null          $notifiable FQCN du modèle notifiable (ex: App\Models\User)
     * @param int|null             $notifiableId ID du modèle à notifier
     * @param string|null          $notification FQCN de la notification à envoyer
     */
    public function __construct(
        public readonly string  $view,
        public readonly array   $data,
        public readonly string  $outputPath,
        public readonly array   $options         = [],
        public readonly ?string $tenantId        = null,
        public readonly ?int    $notifiableId    = null,
        public readonly ?string $notifiable      = null,
        public readonly ?string $notification    = null,
    ) {}

    public function handle(): void
    {
       if ($this->tenantId) {
            tenancy()->initialize($this->tenantId);
        }

        try {
            $html = view($this->view, $this->data)->render();

            // Configure la locale française
            Carbon::setLocale('fr');

            // Crée la date actuelle avec timezone (exemple Africa/Abidjan)
            $now = Carbon::now('Africa/Abidjan');

            $name = env('APP_NAME');

            $formattedDate = 'Générée et imprimée sur la plateforme ' . $name . ' le ' 
                . $now->isoFormat('dddd D MMMM YYYY')  // Ex: lundi 25 mai 2025
                . ' à ' 
                . $now->isoFormat('HH\H mm\min ss\s'); // Ex: 10H 15min 24s

            $header_title = $this->data['pdf_title'] ?? 'Document ' . ' Généré et imprimée sur la plateforme ' . $name;

            $headerHtml = '<div style="font-size:10px; width:100%; text-align:center; color:gray;">'
                . $header_title
                . '</div>';

            $footerHtml = '<div style="font-size:10px; width:100%; text-align:center; color:black;">'
            . $formattedDate
            . ' | Page <span class="pageNumber"></span> / <span class="totalPages"></span>'
            . '</div>';

            $browsershot = Browsershot::html($html)
                ->setNodeBinary(config('browsershot.node_binary'))
                ->setNpmBinary(config('browsershot.npm_binary'))
                ->setChromePath(config('browsershot.chrome_binary'))
                ->timeout(config('browsershot.timeout'))
                ->addChromiumArguments(config('browsershot.chromium_args'))
                ->setIncludePath(public_path('build/assets'))
                ->ignoreHttpsErrors()
                ->showBackground()
                ->margins(15, 15, 15, 15)
                ->showBrowserHeaderAndFooter() // Active l'affichage du header/footer
                ->headerHtml($headerHtml)
                ->footerHtml($footerHtml)
                ->waitUntilNetworkIdle();

            if (config('browsershot.no_sandbox')) {
                $browsershot->noSandbox();
            }

            $this->applyOptions($browsershot);

            $browsershot->save($this->outputPath);

            $this->robotNotifyer();

        }
        catch (\Throwable $th){

            broadcast(new AnyErrorEvent('error', $th->getMessage(), $this->tenantId, $this->notifiableId));
            
            $this->fail($th->getMessage()); 
            
            return;

        }
         finally {
            if ($this->tenantId) {
                tenancy()->end();
            }
        }
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
        if (! $this->notifiableId) return;

        $user = User::find($this->notifiableId);

        $url  = $this->resolvePublicUrl();

        // Sauvegarde le document généré
        GeneratedDocument::create([
            'type'                   => $this->options['document_type'] ?? 'document',
            'filename'               => basename($this->outputPath),
            'path'                   => $this->outputPath,
            'url'                    => $url,
            'user_id'                => $this->notifiableId,
            'tenant_id'              => $this->tenantId,
            'downloadable_by_others' => $this->options['downloadable_by_others'] ?? false,
        ]);

        // Notifie
        $user?->notify(new PDFIsReady(
            title:     "PDF EST PRÊT",
            message:   "Votre document a été bien généré et est à présent prêt!",
            type:      "success",
            url:       $url,
            tenantId:  $this->tenantId,
            userEmail: $user->email,
        ));

    }

    /**
     * Résout l'URL publique à partir du chemin de stockage.
     */
    private function resolvePublicUrl(): string
    {
        return PDFFactory::resolvePublicUrl($this->outputPath);
    }
    
}
