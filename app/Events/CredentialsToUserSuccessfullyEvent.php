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

class CredentialsToUserSuccessfullyEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $tenantId, 
        public string $userEmail,
        public string $role,
        public ?string $msg = null,
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
        
        $user = User::firstWhere('email', $this->userEmail);

        $email = $this->userEmail;

        $msg = "Données de connexion envoyée avec succès à " . $email;

        $full_name = $user?->getUserNamePrefix(true, true) ?? $this->role;

        if($this->msg){

            $msg = $this->msg;
        }
        else{

            $msg = "L'envoi des données à " . $full_name ?? $this->userEmail . '' . $this->role ? " (" . $this->role . ") " : '' . " dont l'adresse mail est " . $email . " s'est bien déroulé!";
        }

        return ['tenantId' => $this->tenantId, 'msg' => $msg, 'userName' => $full_name ?? $this->userEmail, 'email' => $email];
    }


    public function broadcastAs(): string
    {
        return 'credentials.sent.to.user.successfully'; 
    }
}
