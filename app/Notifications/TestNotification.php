<?php

namespace App\Notifications;


use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Session;

class TestNotification extends Notification
{

    public function via($notifiable)
    {
        return [ExpoChannel::class];
    }

    public function routeNotificationForExpoPushNotifications()
    {
        return Session::get('exp_token');
    }
    public function toExpoPush($notifiable)
    {
        return ExpoMessage::create()
            ->badge(1)
            ->enableSound()
            ->setChannelId(Session::get('exp_token'))
            ->title("Congratulations!")
            ->body("Your {$notifiable->service} account was approved!");
    }
}
