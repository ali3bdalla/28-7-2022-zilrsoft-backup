<?php

namespace App\Jobs\Sales\Order;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\ShippingMethod;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSalesOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $orderAutoCancelAfter = 20;
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * @var Request
     */
    private $request;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     * @param Request $request
     */
    public function __construct(Invoice $invoice, Request $request)
    {
        //
        $this->invoice = $invoice;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return Order|null
     */
    public function handle()
    {
        $invoiceItems = $this->invoice->items()->withoutGlobalScope('draft')->get();


        $order = new Order();
        $order->user_id = $this->invoice->user_id;
        $order->shipping_address_id = $this->request->input('shipping_address_id');
        $order->payment_method = $this->request->input('payment_method_id');
        $order->shipping_method_id = $this->request->input('shipping_method_id');
        $order->draft_id = $this->invoice->id;
        $order->shipping_amount = $this->getShippingAmount($invoiceItems);
        $order->shipping_cost = $this->getShippingCost($invoiceItems);
        $order->shipping_weight = $this->getItemsTotalShippingWeight($invoiceItems);
        $order->net = (float)$this->invoice->net;
        $order->auto_cancel_at = Carbon::now()->addMinutes($this->orderAutoCancelAfter);
        $order->is_should_pay_notified = false;
        $order->should_pay_last_notification_at = Carbon::now()->addMinutes($this->orderAutoCancelAfter - 1);
        $order->order_secret_code = bcrypt(rand(10000, 99999));
        $order->status = 'issued';
        $order->save();
        return $order->fresh();
    }

    private function getShippingAmount($invoiceItems)
    {

        $shippingMethod = $this->getShippingMethod();
        $itemsTotalWeight = $this->getItemsTotalShippingWeight($invoiceItems);
        $itemsTotalShippingDiscount = $this->getItemsTotalShippingDiscount($invoiceItems);
        $shippingAmount = $this->getItemsBaseShippingAmount($itemsTotalWeight, $shippingMethod);
        $shippingAmount -= $itemsTotalShippingDiscount;
        return $this->finalShippingAmount($shippingAmount);

    }

    private function getShippingMethod()
    {
        return ShippingMethod::find($this->request->input('shipping_method_id'));
    }

    private function getItemsTotalShippingWeight($invoiceItems)
    {
        $itemsTotalWeight = 0;
        foreach ($invoiceItems as $item) {
            $itemsTotalWeight += (float)$item->item->shipping_discount * $item->qty;
        }

        return $itemsTotalWeight;
    }

    private function getItemsTotalShippingDiscount($invoiceItems)
    {
        $itemsTotalShippingDiscount = 0;
        foreach ($invoiceItems as $item) {
            $itemsTotalShippingDiscount += (float)$item->item->shipping_discount * $item->qty;
        }

        return $itemsTotalShippingDiscount;
    }

    private function getItemsBaseShippingAmount($itemsTotalWeight, $shippingMethod)
    {
        $maxShippingMethodWeight = $shippingMethod->max_base_weight;
        $shippingAmount = $shippingMethod->max_base_weight_price;

        if ($itemsTotalWeight > $maxShippingMethodWeight) {
            $kgAfterBase = $itemsTotalWeight - $maxShippingMethodWeight;
            $shippingAmount += $kgAfterBase * $shippingMethod->kg_rate_after_max_price;

        }
        return $shippingAmount;

    }

    private function finalShippingAmount(int $shippingAmount)
    {
        if ($shippingAmount < 0) {
            $shippingAmount = 0;
        }
        return $shippingAmount;

    }

    private function getShippingCost($invoiceItems)
    {
        $shippingMethod = $this->getShippingMethod();
        $maxShippingMethodWeight = $shippingMethod->max_base_weight;
        $shippingCost = $shippingMethod->max_base_weight_cost;
        $itemsTotalWeight = $this->getItemsTotalShippingWeight($invoiceItems);
        if ($itemsTotalWeight > $maxShippingMethodWeight) {
            $kgAfterBase = $itemsTotalWeight - $maxShippingMethodWeight;
            $shippingCost += $kgAfterBase * $shippingMethod->kg_rate_after_max_cost;

        }
        return $shippingCost;
    }

}
