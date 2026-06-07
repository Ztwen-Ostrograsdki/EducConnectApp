<?php

namespace App\Jobs;

use App\Mail\MailToNotifyUserToAccessToAccountAfterCreationByDirector;
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
class JoToNotifyUserToAccessToAccountAfterCreationByDirector implements ShouldQueue
{
    use Queueable;

    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ?string $tenantId,
        public ?User $user = null,
        public ?string $default_password = null,
        public ?string $role_name = null,
        public ?string $domain = null
    )
    {
        if(!$default_password){
            $this->default_password = Str::random(8);
        }
    }

    public function middleware(): array
    {
        $user = $this->user;
        return [
            Skip::when(!$user || ($user && $user->logged_count > 0)),
        ];
    }

    public function handle(): void
    {
        try {
            $user = $this->user;

            if ($user) {

                $default_password = $this->default_password;

                $user->update(['password' => Hash::make($default_password)]);

            }

            $url = $this->domain . "/login";

            $greating = $user?->greating();

            $userName = $user?->getFullName();

            $role = $this->role_name;

            $receiver_html = EmailTemplateBuilder::render('mail-to-notify-user-to-access-to-account-after-creating-by-director', [
                'space_url'              => $url,
                'for_greating'           => $greating,
                'key'                    => $this->default_password,
                'full_name'              => $userName,
                'contacts'               => $user->contacts,
                'email'                  => $user->email,
                'role'                   => $role,
                'school_name'            => $user->school_name,
                'simple_name'            => $user->simple_name,
                'domain'                 => $this->domain,
            ]);

            Mail::to($user->email)->queue(
                new MailToNotifyUserToAccessToAccountAfterCreationByDirector(
                    $userName,     
                    $receiver_html
                )
            );

        } catch (\Throwable $th) {
            // broadcast(new FailedToSendCredentialsToCreatedTenantEvent($this->tenant, $th->getMessage()));
        }
    }
}
