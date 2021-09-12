<?php

namespace App\Notifications\Store;

use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StoreWelcomeNotification extends Notification implements WhatsappMessageNotificationContract
{
    use Queueable;


    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return [WhatsappMessageChannel::class];
    }


    public function toWhatsappMessage($notifiable): string
    {
        return "welcome to our store : https://msbrshop.com";
    }
}
