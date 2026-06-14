<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\PDFIsReady;
use App\Services\PDFFactory;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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

            File::ensureDirectoryExists(dirname($this->outputPath));

            $browsershot = Browsershot::html($html)
                ->setNodeBinary(config('browsershot.node_binary'))
                ->setNpmBinary(config('browsershot.npm_binary'))
                ->setChromePath(config('browsershot.chrome_binary'))
                ->timeout(config('browsershot.timeout'))
                ->addChromiumArguments(config('browsershot.chromium_args'))
                ->showBackground()
                ->waitUntilNetworkIdle();

            if (config('browsershot.no_sandbox')) {
                $browsershot->noSandbox();
            }

            $this->applyOptions($browsershot);

            $browsershot->save($this->outputPath);

            $this->robotNotifyer();

        } finally {
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
            'margins'   => fn ($v) => $browsershot->margins(...$v),
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
        if (! $this->notifiableId ) {
            return;
        }

        $user_receiver = User::find($this->notifiableId);

        if ($user_receiver) {
            $user_receiver->notify(new PDFIsReady("PDF EST PRET", "Votre document a été bien généré et est à présent prêt!", "info", $this->resolvePublicUrl(), $this->tenantId, $user_receiver->email));
        }
    }

    /**
     * Résout l'URL publique à partir du chemin de stockage.
     */
    private function resolvePublicUrl(): string
    {
        return PDFFactory::resolvePublicUrl($this->outputPath);
    }
    
}
