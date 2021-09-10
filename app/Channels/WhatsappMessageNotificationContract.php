<?php

namespace App\Channels;

interface WhatsappMessageNotificationContract
{
    public function toWhatsappMessage($notifiable): string;
}
