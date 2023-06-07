<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Demandes;
class DemandeStatusNotification extends Notification
{
    use Queueable;
    protected $demandesValides;

    /**
     * Create a new notification instance.
     */
    public function __construct($demandesValides)
   
    {
        $this->demandesValides = $demandesValides;
    
    }
        //
    

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->demandesValides->user->id,

            'demande_id' => $this->demandesValides->id,
            //'montant' => $this->demande->montant,
            'text' => 'Le statut de votre Demande a ete Mis a jour'
            //
        ];
    }
}

