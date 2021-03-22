<?php

namespace App\Package;

use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Whatsapp
{
    /**
     * @param $message
     * @param $phoneNumber
     * @param bool $addSignature
     */
    public static function sendMessage($message, $phoneNumber, $addSignature = true)
    {
        $client = HttpClient::create();
        $appUrl = config('app.url');
        $appName = trans('store.app.name');
        $appCustomerSupport = trans('store.common.customer_support');
        $contentSupportNote = trans('store.common.customer_support_note');

        if ($addSignature) {
            $message = "$message\n\n$appCustomerSupport\nhttps://tinyurl.com/2eol5vxz\n$appName\n$appUrl\n$contentSupportNote";
        }
        $data = [
            'query' => [
                'body' => $message,
                'phone' => $phoneNumber,
                'token' => config('services.whatsapp.token')
            ]
        ];

        try {
            $client->request(
                'GET', config('services.whatsapp.base_url') . 'sendMessage' . "?token=" . config('services.whatsapp.token'), $data
            );

        } catch (TransportExceptionInterface $e) {

        } catch (ClientException $e) {
        }
    }

    public static function sendFile($storagePath, $phoneNumber, $fileName = "order.pdf")
    {
        $client = HttpClient::create();


        $data = [
            'body' => [
                'body' => $storagePath,
                'filename' => $fileName,
                'phone' => $phoneNumber,

            ]
        ];
        try {
            $client->request(
                'POST', config('services.whatsapp.base_url') . 'sendFile' . "?token=" . config('services.whatsapp.token'), $data
            );

        } catch (TransportExceptionInterface $e) {

        } catch (ClientException $e) {
        }
    }
}


