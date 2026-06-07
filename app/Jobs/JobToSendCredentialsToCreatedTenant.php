<?php

namespace App\Jobs;

use App\Events\FailedToSendCredentialsToCreatedTenantEvent;
use App\Mail\MailToSendSchoolSpaceDataToNewTenantAfterRequestValidation;
use App\Models\RequestToCreateNewTenant;
use App\Models\Tenant;
use App\Models\User;
use App\Services\EmailTemplateBuilder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


#[Tries(3)]
class JobToSendCredentialsToCreatedTenant implements ShouldQueue
{
    use Queueable;

    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Tenant $tenant,
        public ?User $user = null,
        public ?string $default_password = null,
        public ?string $http = null
    )
    {
        if(!$default_password){
            $this->default_password = Str::random(8);
        }
    }

    public function middleware(): array
    {
        $tenant = $this->tenant;

        // Initialiser pour vérifier
        tenancy()->initialize($tenant);
        $user = User::first();
        tenancy()->end(); // OK ici, juste pour le middleware

        return [
            Skip::when(!$tenant),
            Skip::when(!$user || ($user && $user->logged_count > 0)),
        ];
    }

    public function handle(): void
    {
        try {
            $tenant = $this->tenant;

            if (!$tenant) return;

            // Réinitialiser dans handle() indépendamment du middleware
            tenancy()->initialize($tenant);

            $user = User::first();

            if ($user) {
                tenancy()->central(function () use ($tenant) {
                    RequestToCreateNewTenant::where('domain_name', $tenant->id)
                        ->first()
                        ?->update(['validated' => true, 'status' => 'active']);
                });

                $default_password = $this->default_password;
                $user->update(['password' => Hash::make($default_password)]);
                $this->user = $user;
            }

            $lien_for_tenant = $tenant->getDomainName() . "/login";

            $greating = $tenant?->greating();

            $userName = $tenant?->getFullName();

            $receiver_html = EmailTemplateBuilder::render('tenant-space-request-validated-template', [
                'space_url'              => $lien_for_tenant,
                'for_greating'           => $greating,
                'key'                    => $this->default_password,
                'full_name'              => $userName,
                'contacts'               => $tenant->contacts,
                'email'                  => $tenant->email,
                'school_name'            => $tenant->school_name,
                'simple_name'            => $tenant->simple_name,
                'domain'                 => $tenant->getDomainName(),
            ]);

            // Fin du contexte tenant AVANT l'envoi du mail
            tenancy()->end();

            Mail::to($tenant->email)->send(
                new MailToSendSchoolSpaceDataToNewTenantAfterRequestValidation(
                    $userName,      // ← données brutes, pas le modèle
                    $receiver_html
                )
            );

        } catch (\Throwable $th) {
            tenancy()->end(); // Sécurité
            broadcast(new FailedToSendCredentialsToCreatedTenantEvent($this->tenant, $th->getMessage()));
        }
    }
}
