<?php

namespace App\Jobs;

use App\Events\AnyErrorEvent;
use App\Mail\MailToSendPasswordResetTokenToUser;
use App\Models\PasswordTokenForReset;
use App\Models\User;
use App\Services\EmailTemplateBuilder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Support\Facades\Mail;

#[Tries(3)]
class JobToSendPasswordResetTokenToUser implements ShouldQueue
{
    use Queueable;

    public $deleteWhenMissingModels = true;

    public User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public PasswordTokenForReset $password_reset_token,
        public string $code,
        public string $token,
        public string $domain,
        public ?string $tenantId = null,
    )
    {
        $this->user = User::where('email', $password_reset_token->email)->first();
    }

    public function middleware(): array
    {
        $password_reset_token = $this->password_reset_token;

        $user = $this->user;

        return [
            Skip::when(!$user || ($user && !$user->email_verified_at)),
            Skip::when(!$password_reset_token || ($password_reset_token && $password_reset_token->used_at)),
        ];
    }

    public function handle(): void
    {

        try {

            $domain = $this->domain;

            $password_reset_token = $this->password_reset_token;

            $user = $this->user;

            if (!$password_reset_token || !$user) return;

            $greating = $user?->greating();

            $url = $domain . "/" . "password-reset/" . $this->token . "/" . $user->email;

            $receiver_html = EmailTemplateBuilder::render('send-password-reset-token-template', [
                'for_greating'           => $greating,
                'key'                    => $this->code,
                'email'                  => $user->email,
                'school_name'            => $user->school_name,
                'domain'                 => $domain,
                'url'                    => $url,
            ]);

            Mail::to($this->password_reset_token->email)->queue(
                new MailToSendPasswordResetTokenToUser(
                    $receiver_html
                )
            );

        } catch (\Throwable $th) {

            broadcast(new AnyErrorEvent(
                "Une erreur s'est produite lors de l'envoie de la clé de restauration du mot de passe au " . $this->password_reset_token->email,
                cutter($th?->getMessage(), 100),
                $this->tenantId,
                $this->user->id,
            ));
            
        }
    }
}
