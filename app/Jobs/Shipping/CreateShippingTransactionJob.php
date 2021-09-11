<?php

namespace App\Jobs\Shipping;

use App\Jobs\External\Smsa\SmsaCreateShippmentJob;
use App\Models\Order;
use App\Models\ShippingMethod;
use App\Models\ShippingTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateShippingTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var ShippingMethod
     */
    private $shippingMethod;
    /**
     * @var array
     */
    private $data;

    /**
     * Create a new job instance.
     *
     * @param ShippingMethod $shippingMethod
     * @param array $data
     */
    public function __construct(ShippingMethod $shippingMethod, $data = [])
    {
        //
        $this->shippingMethod = $shippingMethod;
        $this->data = collect($data);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $shippingTransaction = null;
        $data = $this->data->toArray();
        $data['shipping_method_id'] = $this->shippingMethod->id;
        $data['creator_id'] = auth()->user()->id;
        $data['organization_id'] = auth()->user()->organization_id;
        $order = null;

        if ($this->data->has('order_id')) {
            $orderTransaction = ShippingTransaction::where('order_id', $this->data->get('order_id'))->first();
            $order = Order::find($this->data->get('order_id'));
            if ($orderTransaction) {
                $shippingTransaction = $orderTransaction;
            }
        }


        if (!$shippingTransaction) {
            $refernence = ShippingTransaction::where('reference', $this->data->get('reference'))->first();
            if ($refernence) {
                $data['reference'] = rand(100000000000, 99999999999999);
            }

            if ($this->shippingMethod->id == 2) // smsa
                $data['tracking_number'] = SmsaCreateShippmentJob::dispatchSync($data);
            else {
                $data['tracking_number'] = rand(100000000000, 99999999999999);
            }
            $shippingTransaction = ShippingTransaction::create($data);
            if ($order) {
                $order->update([
                    'tracking_number' => $data['tracking_number']
                ]);
            }
        }
        return $shippingTransaction;

    }
}
