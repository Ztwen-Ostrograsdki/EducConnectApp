<?php

namespace App\Jobs;

use App\Events\AnyErrorEvent;
use App\Mail\MailToShareDocumentToSomeone;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\RealTimeNotification;
use App\Services\EmailTemplateBuilder;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

#[Tries(2)]
#[Timeout(200)]
class JobToShareDocumentToSomeone implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $deleteWhenMissingModels = true;

    public function __construct(
        public string $tenantId,
        public $file_path,
        public array $receiverIds,
        public string $title,
        public ?string $space_url = null,
    ) {
        
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

            $director = User::firstWhere('tenant_id', $this->tenantId);

            

            $receiver_html = EmailTemplateBuilder::render('mail-to-share-document-to-someone', [
                'space_url'    => $this->space_url,
                'title'       => $this->title,
                'school_name'  => $tenant->school_name,
                'domain'       => $tenant->getDomainName(),
            ]);

            foreach($this->receiverIds as $userId){

                $user = User::find($userId);

                if ($user) {

                    Mail::to($user->email)->queue(
                        new MailToShareDocumentToSomeone(
                            $this->file_path,
                            $this->title,
                            $receiver_html,
                        )
                    );

                }
            }

        } catch (\Throwable $th) {
            
            $director->notify(new RealTimeNotification(
                userEmail: $director->email,
                tenantId: $this->tenantId,
                title:             "Echec de l'envoi du document ",
                message:           cutter($th->getMessage(), 1000),
                type:              'error',
            ));
            throw $th; 
        } finally {
            tenancy()->end(); 
        }
    }
}
