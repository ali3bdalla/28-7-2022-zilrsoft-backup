<?php

namespace App\Channels;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WhatsappFileChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param WhatsappFileNotificationContract $notification
     * @return void
     */
    public function send($notifiable, WhatsappFileNotificationContract $notification)
    {
        $file = $notification->toWhatsappFile($notifiable);
        $client = HttpClient::create();
        $data = [
            'body' => [
                'body' => $file['path'],
                'filename' => $file['name'],
                'phone' => $notifiable->whatsappPhoneNumber(),
            ]
        ];
        try {
            $client->request(
                'POST', config('services.whatsapp.base_url') . 'sendFile' . "?token=" . config('services.whatsapp.token'), $data
            );
        } catch (TransportExceptionInterface | ClientException $e) {
            Log::critical("whatsapp connection not working", $e->getTrace());
        }
    }
}
