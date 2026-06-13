<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FailedToSendCredentialsToUserEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

   /**
     * Create a new event instance.
     */
    public function __construct(
        public string $tenantId, 
        public string $userEmail,
        public string $role,
        public ?string $error = null,
    )
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('tenant.' . $this->tenantId . '.directeur'),
        ];
    }

    public function broadcastWith(): array
    {
        $user = User::firstWhere('email',  $this->userEmail);

        $error = "Une erreur est survenue";

        $full_name = $user?->getUserNamePrefix(true, true) ?? 'Enseignant';

        if($this->error){

            $error = $this->error;
        }
        else{

            $error = "L'envoi des données à " . $full_name ?? $this->userEmail . '' . $this->role ? " (" . $this->role . ") " : '' ." dont l'adresse mail est " . $this->userEmail . " a échoué";
        }

        return ['tenantId' => $this->tenantId, 'msg' => $error, 'userName' => $full_name ?? $this->userEmail, 'email' => $this->userEmail];
    }


    public function broadcastAs(): string
    {
        return 'send.credentials.to.teacher.failed'; 
    }
}
