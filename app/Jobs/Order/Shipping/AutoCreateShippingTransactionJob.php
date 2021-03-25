<?php

namespace App\Jobs\Order\Shipping;

use App\Jobs\Shipping\CreateShippingTransactionJob;
use App\Jobs\Shipping\CreateShippingTransactionShippingStatusJob;
use App\Models\DeliveryMan;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class AutoCreateShippingTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Order
     */
    private $order;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        //
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (Auth::user()->delivery_man_id) {
            $deliveryMan = DeliveryMan::find(Auth::user()->delivery_man_id);
            if ($deliveryMan) {
                $data = [
                    'order_id' => $this->order->id,
                    'city_id' => $this->order->shippingAddress->city_id,
                    'first_name' => $this->order->shippingAddress->first_name,
                    'last_name' => $this->order->shippingAddress->last_name,
                    'address' => $this->order->shippingAddress->description,
                    'phone_number' => $this->order->shippingAddress->phone_number,
                    'reference' => $this->order->id,
                    'description' => 'Electornics',
                    'cod' => 0,
                    'boxes' => 1,
                    'weight' => $this->order->shipping_weight,
                ];
                $shippingTransaction = CreateShippingTransactionJob::dispatchNow($this->order->shippingMethod, $data);
                $this->order->update([
                    'status' => 'ready_for_shipping'
                ]);
                CreateShippingTransactionShippingStatusJob::dispatchNow($shippingTransaction, $deliveryMan);
            }


        }

    }
}
