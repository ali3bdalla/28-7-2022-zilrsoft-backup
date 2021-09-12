<?php

namespace App\Notifications;

use App\Channels\OurSmsChannel;
use App\Channels\OurSmsNotificationContract;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification implements OurSmsNotificationContract
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
        return [OurSmsChannel::class];
    }


    public function toOurSms($notifiable): string
    {
        return "test-message";
    }
}
