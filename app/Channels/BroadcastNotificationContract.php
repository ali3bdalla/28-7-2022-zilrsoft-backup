<?php

namespace App\Channels;

use App\Dto\BroadcastNotificationDto;

interface BroadcastNotificationContract
{
    public function toBroadcast($notifiable): BroadcastNotificationDto;
}
