<?php

namespace App\Jobs\Order;

use App\Models\Order;
use App\Package\Whatsapp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyCustomerByPaymentConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $phoneNumber = $this->order->user->international_phone_number;
        $message = __('store.messages.order_payment_confirmed', [
            'CUSTOMER_NAME' => $this->order->user->name,
            'ORDER_ID' => $this->order->id,
        ]);

        if (config('app.store.notify_via_sms')) {
            sendSms($message, $phoneNumber);
        }
        if (config('app.store.notify_via_whatsapp')) {
            Whatsapp::sendMessage(
                $message,
                $phoneNumber,
                false
            );

        }
    }
}
