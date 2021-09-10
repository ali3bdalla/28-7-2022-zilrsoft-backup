<?php

namespace App\Channels;

interface WhatsappFileNotificationContract
{
    public function toWhatsappFile($notifiable): array;
}
