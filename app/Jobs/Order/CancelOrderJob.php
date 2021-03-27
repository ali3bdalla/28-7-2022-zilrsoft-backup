<?php

namespace App\Jobs\Order;

//	use AliAbdalla\Whatsapp\Whatsapp;
use App\Jobs\Items\AvailableQty\UpdateAvailableQtyByInvoiceItemJob;
use App\Models\Order;
use App\Package\Whatsapp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CancelOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    private $order;
    private $isManual;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     * @param $isManual
     */
    public function __construct(Order $order, $isManual = false)
    {
        //
        $this->order = $order;
        $this->isManual = $isManual;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $message = __("store.messages.unpaid_order_canceled_message", ["CUSTOMER_NAME" => $this->order->user->name, "ORDERID" => $this->order->id, 'REASON' => $this->isManual ? __('store.messages.as_your_request') : __('store.messages.not_paid')]);
        $this->order->update(
            [
                'status' => 'canceled'
            ]
        );
        $this->unHoldItemQuantity();
        if (config('app.store.notify_via_sms')) {
            sendSms($message, $this->order->user->phone_number);
        }

        if (config('app.store.notify_via_whatsapp')) {
            Whatsapp::sendMessage($message, $this->order->user->international_phone_number);

        }

    }

    private function unHoldItemQuantity()
    {
        foreach ($this->order->itemsQtyHolders as $holdQty) {
            UpdateAvailableQtyByInvoiceItemJob::dispatchNow($holdQty->invoiceItem, true);
            $holdQty->update(
                [
                    'status' => 'destroyed'
                ]
            );
        }

    }
}
