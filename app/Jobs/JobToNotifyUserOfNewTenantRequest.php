<?php

namespace App\Jobs;

use App\Events\AnyErrorEvent;
use App\Mail\MailToNotifyUserOfNewTenantRequest;
use App\Models\RequestToCreateNewTenant;
use App\Services\EmailTemplateBuilder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Support\Facades\Mail;
use Throwable;

#[Timeout(300)]
class JobToNotifyUserOfNewTenantRequest implements ShouldQueue
{
    use Queueable;

    public bool $deleteWhenMissingModels = true;

    /**
     * Seul l'ID est sérialisé — évite que QueueTenancyBootstrapper
     * lise un tenant_id vide depuis le modèle RequestToCreateNewTenant.
     *
     * @param int $req_id
     */
    public function __construct(
        public int $req_id,
    ) {}

    /**
     * @return array
     */
    public function middleware(): array
    {
        $req = RequestToCreateNewTenant::find($this->req_id);

        return [
            Skip::when(! $req || $req->validated),
        ];
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        /** @var RequestToCreateNewTenant|null $req */
        $req = RequestToCreateNewTenant::find($this->req_id);

        if (! $req) {
            $this->fail();
            return;
        }

        $adresse   = $req->country . ', ' . $req->adresse;
        $userName  = $req->getFullName();
        $userEmail = $req->email;
        $greating  = $req->greating();

        $receiver_html = EmailTemplateBuilder::render('email-to-user-for-school-space-request', [
            'for_greating'      => $greating,
            'full_name'         => $userName,
            'school_name'       => $req->school_name,
            'simple_name'       => $req->simple_name,
            'domain'            => $req->domain_name,
            'school_type'       => $req->school_type,
            'enseignement_type' => $req->enseignement_type,
            'periode_type'      => $req->periode_type,
            'adresse'           => $adresse,
            'email'             => $userEmail,
            'gender'            => $req->gender,
            'contacts'          => $req->contacts,
        ]);

        Mail::to($userEmail)->send(
            new MailToNotifyUserOfNewTenantRequest(
                $userName,
                $receiver_html
            )
        );
    }

    /**
     * @param Throwable|null $exception
     * @return void
     */
    public function failed(?Throwable $exception): void
    {
        report($exception);

        $req = RequestToCreateNewTenant::find($this->req_id);

        broadcast(new AnyErrorEvent(
            "Erreur de l'envoie par mail de l'accusé de reception à " . $req?->domain_name,
            cutter($exception?->getMessage(), 100)
        ));
    }
}