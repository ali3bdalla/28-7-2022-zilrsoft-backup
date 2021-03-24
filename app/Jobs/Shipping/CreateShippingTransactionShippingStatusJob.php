<?php

namespace App\Jobs\Shipping;

use App\Jobs\Order\Shipping\HandleOrderShippingJob;
use App\Models\DeliveryMan;
use App\Models\ShippingTransaction;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateShippingTransactionShippingStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ShippingTransaction
     */
    private $shippingTransaction;
    /**
     * @var DeliveryMan
     */
    private $deliveryMan;

    /**
     * Create a new job instance.
     *
     * @param ShippingTransaction $shippingTransaction
     * @param DeliveryMan $deliveryMan
     */
    public function __construct(ShippingTransaction $shippingTransaction, DeliveryMan $deliveryMan)
    {
        //
        $this->shippingTransaction = $shippingTransaction;
        $this->deliveryMan = $deliveryMan;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->shippingTransaction->update([
            'status' => 'shipped',
            'shipped_at' => Carbon::now(),
            'delivery_man_id' => $this->deliveryMan->id
        ]);
        if ($this->shippingTransaction->order && $this->shippingTransaction->order->status == 'ready_for_shipping') {
            HandleOrderShippingJob::dispatchNow($this->shippingTransaction->order, $this->deliveryMan);
            if ($this->shippingTransaction->order->shipping_amount > 0)
                CreateShippingSalesInvoiceJob::dispatchNow($this->shippingTransaction->order->user, $this->shippingTransaction, $this->shippingTransaction->order->shipping_amount);
        }
    }
}
