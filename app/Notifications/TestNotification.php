<?php

namespace App\Notifications;

use App\Channels\OurSmsChannel;
use App\Channels\OurSmsNotificationContract;
use App\Channels\WhatsappMessageChannel;
use App\Channels\WhatsappMessageNotificationContract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification implements WhatsappMessageNotificationContract
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

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


    public function toOurSms($notifiable): string
    {
        return "test-message";
    }

    public function toWhatsappMessage($notifiable): string
    {
        return "test";
    }
}
