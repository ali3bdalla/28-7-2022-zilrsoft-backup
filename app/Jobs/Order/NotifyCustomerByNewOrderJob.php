<?php

namespace App\Jobs\Order;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use App\Package\Whatsapp;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class NotifyCustomerByNewOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    private $order;
    private $path;
    /**
     * @var User
     */
    private $client;
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     * @param $path
     * @param User $client
     * @param Invoice $invoice
     */
    public function __construct(Order $order, $path = "",  Invoice $invoice)
    {
        //
        $this->order = $order;
        $this->path = $path;
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $phoneNumber = $this->order->user->international_phone_number;
        $message = __('store.messages.notify_customer_by_new_order_message',[
            'CUSTOMER_NAME' => $this->order->user->name,
            'CANCEL_URL' => $this->order->generateCancelOrderUrl(),
            'PAYMENT_URL' => $this->order->generatePayOrderUrl(),
            'DEADLINE_TIME' => Carbon::now()->addMinutes(config('app.store.cancel_unpaid_orders_after'))->format('H:i'),
            'DEADLINE_DATE' => Carbon::now()->toDateString(),
            'AMOUNT' => displayMoney($this->order->net) .' '. __('store.products.sar'),
            'ORDER_ID' => $this->order->id,
        ]);

         $paymentLinkMessage = __('store.messages.notify_customer_by_new_order_message_payment_link',[
            'CUSTOMER_NAME' => $this->order->user->name,
            'CANCEL_URL' => $this->order->generateCancelOrderUrl(),
            'PAYMENT_URL' => $this->order->generatePayOrderUrl(),
            'DEADLINE_TIME' => Carbon::now()->addMinutes(config('app.store.cancel_unpaid_orders_after'))->format('H:i'),
            'DEADLINE_DATE' => Carbon::now()->toDateString(),
            'AMOUNT' => displayMoney($this->order->net) .' '. __('store.products.sar'),
            'ORDER_ID' => $this->order->id,
        ]);



        if (config('app.store.notify_via_sms')) {
            sendSms($message, $phoneNumber);
            sendSms($paymentLinkMessage, $phoneNumber);
        }
        if (config('app.store.notify_via_whatsapp')) {
            Whatsapp::sendMessage(
                $message,
                $phoneNumber
            );
            Whatsapp::sendMessage(
                $paymentLinkMessage,
                $phoneNumber
            );
        }
    }
}
