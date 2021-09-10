<?php

namespace App\Listeners\Order;

use App\Package\Whatsapp;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PDF;


class SendOrderToClientViaWhatsappListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {

        $phoneNumber = $event->order->user->international_phone_number;
        $message = view(
            'whatsapp.order_details',
            [
                'client' => $event->client,
                'order' => $event->order,
                'invoice' => $event->invoice,
                'deadline' => Carbon::now()->addMinutes(30)->format('H:i')
            ]
        )->toHtml();

        if (config('app.store.notify_via_sms')) {
            sendSms($message, $phoneNumber);
        }
        if (config('app.store.notify_via_whatsapp')) {
            Whatsapp::sendMessage(
                $message,
                $phoneNumber
            );
            Whatsapp::sendFile(
                Storage::url($event->path),
                $phoneNumber,
                $event->invoice->id . '.pdf'
            );
        }

    }
}
