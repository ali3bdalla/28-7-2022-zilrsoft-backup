<?php

namespace App\Channels;

use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WhatsappMessageChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param WhatsappMessageNotificationContract $notification
     * @return void
     */
    public function send($notifiable, WhatsappMessageNotificationContract $notification)
    {
        $message = $notification->toWhatsappMessage($notifiable);
        dd($notifiable->useAsWhatsappTargetPhoneNumber());
        $client = HttpClient::create();
        $data = [
            'query' => [
                'body' => $message,
                'phone' => $notifiable->useAsWhatsappTargetPhoneNumber(),
                'token' => config('services.whatsapp.token')
            ]
        ];

        try {
            $client->request(
                'GET', config('services.whatsapp.base_url') . 'sendMessage' . "?token=" . config('services.whatsapp.token'), $data
            );
        } catch (TransportExceptionInterface | ClientException $e) {

        }

    }
}
