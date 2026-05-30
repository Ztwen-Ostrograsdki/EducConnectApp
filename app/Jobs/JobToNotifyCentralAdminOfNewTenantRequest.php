<?php

namespace App\Jobs;

use App\Events\AnyErrorEvent;
use App\Mail\MailToNotifyCentralAdminOfNewTenantRequest;
use App\Models\CentralUser;
use App\Models\RequestToCreateNewTenant;
use App\Services\EmailTemplateBuilder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Support\Facades\Mail;
use Throwable;

class JobToNotifyCentralAdminOfNewTenantRequest implements ShouldQueue
{
    use Queueable;

    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public RequestToCreateNewTenant $req,
    )
    {
    }

    public function middleware(): array
    {
        $req = $this->req;

        return [
            Skip::when(!$req || $req->validated),
        ];
    }

    public function handle(): void
    {
        $req = $this->req;

        if (!$req) {

            $this->fail();

            return;
        }

        $adresse = $req->country . ', ' . $req->adresse;

        $userName = $req?->getFullName();

        $userEmail = $req?->email;

        $receiver_html = EmailTemplateBuilder::render('email-to-central-for-school-space-request', [
            'full_name'        => $userName,
            'school_name'      => $req?->school_name,
            'simple_name'      => $req?->simple_name,
            'domain'           => $req?->domain_name,
            'school_type'      => $req?->school_type,
            'enseignement_type'=> $req?->enseignement_type,
            'periode_type'     => $req?->periode_type,
            'adresse'          => $adresse,
            'email'            => $userEmail,
            'gender'           => $req?->gender,
            'contacts'         => $req?->contacts,
        ]);

        $centrailMail = CentralUser::first()?->email;

        Mail::to($centrailMail)->queue(
            new MailToNotifyCentralAdminOfNewTenantRequest(
                $userName,      
                $receiver_html
            )
        );
    }

    public function failed(? Throwable $exception)
    {
        report($exception);

        $target = "Erreur de l'envoie par mail de l'accusé de reception au central";

        $message = $exception?->getMessage();

        broadcast(new AnyErrorEvent($target, $message));

    }
}
