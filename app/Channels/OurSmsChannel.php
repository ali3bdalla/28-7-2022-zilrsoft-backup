<?php

namespace App\Channels;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class OurSmsChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param OurSmsNotificationContract $notification
     * @return void
     */
    public function send($notifiable, OurSmsNotificationContract $notification)
    {
        $message = $notification->toOurSms($notifiable);
        $client = HttpClient::create();
        $options = [
            'auth_basic' => [config('services.our_sms.username'), config('services.our_sms.password')],
            'query' => [
                'sender' => config('services.our_sms.sender'),
                'message' => urlencode($message),
                'unicode' => "E",
                'return' => "full",
                'numbers' => $notifiable->useAsOurSmsTargetPhoneNumber(),
            ]
        ];
        try {
            $client->request(
                'GET', config('services.our_sms.base_url'), $options
            );
        } catch (TransportExceptionInterface | ClientException $e) {
            Log::critical("Our Sms connection not working", $e->getTrace());

        }
    }
}
