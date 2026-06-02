<?php

namespace App\Jobs;

use App\Mail\MailToNotifyUserAfterPasswordUpdated;
use App\Models\PasswordTokenForReset;
use App\Models\User;
use App\Services\EmailTemplateBuilder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Support\Facades\Mail;


#[Tries(3)]
class JobToNotifyUserAfterPasswordUpdated implements ShouldQueue
{
    use Queueable;

    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public string $domain,
    )
    {
    }

    public function middleware(): array
    {
        $user = $this->user;

        return [
            Skip::when(!$user),
        ];
    }

    public function handle(): void
    {

        try {

            $domain = $this->domain;

            $user = $this->user;

            if (!$user) return;

            $greating = $user?->greating();

            $receiver_html = EmailTemplateBuilder::render('notify-user-after-password-update-template', [
                'for_greating'           => $greating,
                'email'                  => $user->email,
                'school_name'            => $user->school_name,
                'domain'                 => $domain,
                'url'                    => $domain . '/mon-profil',
            ]);

            // Fin du contexte tenant AVANT l'envoi du mail

            Mail::to($this->user->email)->queue(
                new MailToNotifyUserAfterPasswordUpdated(
                    $receiver_html
                )
            );

        } catch (\Throwable $th) {
            
        }
    }
}
