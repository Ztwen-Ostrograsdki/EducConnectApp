<?php

namespace App\Jobs;

use App\Events\AnyErrorEvent;
use App\Events\CredentialsSentToCreatedTenantSucessfullyEvent;
use App\Events\FailedToSendCredentialsToCreatedTenantEvent;
use App\Mail\MailToSendSchoolSpaceDataToNewTenantAfterRequestValidation;
use App\Models\RequestToCreateNewTenant;
use App\Models\Tenant;
use App\Models\User;
use App\Services\EmailTemplateBuilder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

#[Tries(3)]
class JobToSendCredentialsToCreatedTenant implements ShouldQueue
{
    use Queueable;

    public $deleteWhenMissingModels = true;

    public function __construct(
        public string $tenantId,
        public ?string $default_password = null,
        public ?string $space_url = null
    ) {
        if (!$default_password) {
            $this->default_password = Str::random(8);
        }
    }

    public function handle(): void
    {
        try {
            $tenant = Tenant::findOrFail($this->tenantId);

            if (!$tenant) {
                broadcast(new AnyErrorEvent("TENANT INEXISTANT", "Le tenant n'existe pas"));
            }

            // Initialisation du tenant
            tenancy()->initialize($tenant);

            $user = User::first();

            if (!$user) {
                broadcast(new FailedToSendCredentialsToCreatedTenantEvent($this->tenantId, "Aucun utilisateur trouvé pour le tenant {$this->tenantId}"));
            }

            // Mise à jour du statut de la demande
            RequestToCreateNewTenant::where('domain_name', $tenant->id)
                ->first()
                ?->update(['validated' => true, 'status' => 'active']);

            // Mise à jour du mot de passe
            $user->update(['password' => Hash::make($this->default_password)]);

            $receiver_html = EmailTemplateBuilder::render('tenant-space-request-validated-template', [
                'space_url'    => $this->space_url,
                'for_greating' => $tenant?->greating(),
                'key'          => $this->default_password,
                'full_name'    => $tenant?->getFullName(),
                'contacts'     => $tenant->contacts,
                'email'        => $tenant->email,
                'school_name'  => $tenant->school_name,
                'simple_name'  => $tenant->simple_name,
                'domain'       => $tenant->getDomainName(),
            ]);

            Mail::to($tenant->email)->send(
                new MailToSendSchoolSpaceDataToNewTenantAfterRequestValidation(
                    $tenant?->getFullName(),
                    $receiver_html
                )
            );

            CredentialsSentToCreatedTenantSucessfullyEvent::dispatch($this->tenantId);

            $tenant->update(['completed' => true]);

        } catch (\Throwable $th) {
            broadcast(new FailedToSendCredentialsToCreatedTenantEvent($this->tenantId, cutter($th->getMessage(), 100)));
            throw $th; // Permet à Laravel de réessayer ou de marquer comme échoué
        } finally {
            tenancy()->end(); // Très important
        }
    }
}