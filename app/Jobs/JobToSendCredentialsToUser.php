<?php

namespace App\Jobs;

use App\Events\AnyErrorEvent;
use App\Events\CredentialsToUserSuccessfullyEvent;
use App\Events\FailedToSendCredentialsToUserEvent;
use App\Mail\MailToSendCredentialsToUser;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Services\EmailTemplateBuilder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

#[Tries(2)]
#[Timeout(200)]
class JobToSendCredentialsToUser implements ShouldQueue
{
    use Queueable;

    public $deleteWhenMissingModels = true;

    public function __construct(
        public string $tenantId,
        public string $userEmail,
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
                broadcast(new AnyErrorEvent("DOMAINE INEXISTANT", "Le domaine n'existe pas"));
            }

            // Initialisation du tenant
            tenancy()->initialize($tenant);

            $user = User::firstWhere('email', $this->userEmail);

            $director = User::firstWhere('tenant_id', $this->tenantId);

            if (!$user) {

                $msg = "Aucun utilisateur | enseignant trouvé  avec l'adresse " . $this->userEmail . " pour l'envoi des données ";

                broadcast(new FailedToSendCredentialsToUserEvent($this->tenantId, $this->userEmail, 'Enseignant', $msg));

                $tenant->user->notify(new RealTimeNotification(
                    userEmail: $tenant->email,
                    tenantId: $this->tenantId,
                    title:             "Echec de l'envoi des données de connexion à " . $this->userEmail,
                    message:           $msg,
                    type:              'error',
                ));

                $this->fail($msg);

                return;
            }

            // Mise à jour du mot de passe
            $user->update(['password' => Hash::make($this->default_password), 'status' => 'active']);

            $roles = $user->myRoles();

            $receiver_html = EmailTemplateBuilder::render('mail-to-notify-user-to-access-to-account-after-creating-by-director', [
                'space_url'    => $this->space_url,
                'for_greating' => $user?->greating(),
                'key'          => $this->default_password,
                'full_name'    => $user?->getFullName(),
                'contacts'     => $user->contacts,
                'email'        => $user->email,
                'school_name'  => $tenant->school_name,
                'role'         => $roles ?? ' Utilisateur',
                'simple_name'  => $tenant->simple_name,
                'domain'       => $tenant->getDomainName(),
            ]);

            Mail::to($user->email)->queue(
                new MailToSendCredentialsToUser(
                    $user?->getFullName(),
                    $receiver_html
                )
            );

            CredentialsToUserSuccessfullyEvent::dispatch($this->tenantId, $this->userEmail, 'Enseignant');

            $director->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId: $this->tenantId,
                title:             "Envoi des données de connexion à " . $this->userEmail,
                message:           'Données envoyées avec succès.',
                type:              'success',
            ));

            $user->update(['credentials_sent' => true]);

        } catch (\Throwable $th) {
            broadcast(new FailedToSendCredentialsToUserEvent($this->tenantId, $this->userEmail, 'Enseignant', cutter($th->getMessage(), 100)));

            $director->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId: $this->tenantId,
                title:             "Echec de l'envoi des données de connexion à " . $this->userEmail,
                message:           cutter($th->getMessage(), 100),
                type:              'error',
            ));
            throw $th; 
        } finally {
            tenancy()->end(); 
        }
    }
}
