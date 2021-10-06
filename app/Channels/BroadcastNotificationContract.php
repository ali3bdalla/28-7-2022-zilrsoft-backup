<?php

namespace App\Channels;

use Illuminate\Notifications\Messages\BroadcastMessage;

interface BroadcastNotificationContract
{
    public function toBroadcast($notifiable): BroadcastMessage;
}
