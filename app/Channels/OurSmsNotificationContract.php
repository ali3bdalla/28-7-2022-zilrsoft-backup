<?php

namespace App\Channels;


interface OurSmsNotificationContract
{
    public function toOurSms($notifiable): string;
}
