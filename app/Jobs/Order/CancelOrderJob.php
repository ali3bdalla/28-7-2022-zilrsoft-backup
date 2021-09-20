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
    private Order $order;
    private bool $isManual;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     * @param bool $isManual
     */
    public function __construct(Order $order, bool $isManual = false)
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
            UpdateAvailableQtyByInvoiceItemJob::dispatchSync($holdQty->invoiceItem, true);
            $holdQty->update(
                [
                    'status' => 'destroyed'
                ]
            );
        }

    }
}
