<?php

namespace App\Jobs\Order\Shipping;

use App\Jobs\Sales\Expense\CreatePurchaseInvoiceForExpensesJob;
use App\Models\DeliveryMan;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleOrderShippingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $deliveryMan,$order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order,DeliveryMan $deliveryMan)
    {
       $this->order = $order;
       $this->deliveryMan = $deliveryMan;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //

        $this->order->update([
            'delivery_man_id' => $this->deliveryMan->id,
            'status' => 'shipped'
        ]);


        // to create shipping item

        // $item = $this->deliveryMan->shippingMethod->item;

        // dispatch_now(new CreatePurchaseInvoiceForExpensesJob($this->input('items')));
    }
}
