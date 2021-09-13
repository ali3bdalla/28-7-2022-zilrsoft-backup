<?php

namespace App\Jobs\Sales\Order;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\ShippingMethod;
use App\Scopes\DraftScope;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateSalesOrderJob
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * @var Invoice
     */
    private Invoice $invoice;

    /**
     * @var array
     */
    private array $request;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     * @param array $request
     */
    public function __construct(Invoice $invoice, array $request)
    {
        $this->invoice = $invoice;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return Order|null
     */
    public function handle(): ?Order
    {
        $invoiceItems = $this->invoice->items()->with('item')->withoutGlobalScope(DraftScope::class)->get();
        $shippingAmount = $this->getShippingAmount($invoiceItems);
        $shippingCost = $this->getShippingCost($invoiceItems);
        $shippingWeight = $this->getItemsTotalShippingWeight($invoiceItems);
        $order = new Order();
        $order->lang = app()->getLocale();
        $order->user_id = $this->invoice->user_id;
        $order->organization_id = $this->invoice->organization_id;
        $order->shipping_address_id = $this->request['shipping_address_id'];
        $order->payment_method = $this->request['payment_method_id'];
        $order->shipping_method_id = $this->request['shipping_method_id'];
        $order->draft_id = $this->invoice->id;
        $order->shipping_amount = $shippingAmount;
        $order->shipping_cost = $shippingCost;
        $order->shipping_weight = $shippingWeight;
        $order->net = (float)$this->invoice->net + (float)$shippingAmount;
        $order->auto_cancel_at = Carbon::now()->addMinutes(config('app.store.cancel_unpaid_orders_after', 30));
        $order->is_should_pay_notified = false;
        $order->should_pay_last_notification_at = Carbon::now()->addMinutes(config('app.store.notify_unpaid_orders_after', 25));
        $order->order_secret_code = (rand(10000, 99999));
        $order->delivery_man_code = (rand(100, 999));
        $order->status = 'issued';
        $order->save();
        return $order;
    }

    /**
     * @param  $invoiceItems
     * @return int
     */
    private function getShippingAmount($invoiceItems = []): int
    {

        $shippingMethod = $this->getShippingMethod();
        $itemsTotalWeight = $this->getItemsTotalShippingWeight($invoiceItems);
        $itemsTotalShippingDiscount = $this->getItemsTotalShippingDiscount($invoiceItems);
        $shippingAmount = $this->getItemsBaseShippingAmount($itemsTotalWeight, $shippingMethod);
        $shippingAmount -= $itemsTotalShippingDiscount;
        return $this->finalShippingAmount($shippingAmount);
    }

    /**
     * @return mixed
     */
    private function getShippingMethod()
    {
        return ShippingMethod::find($this->request['shipping_method_id']);
    }

    private function getItemsTotalShippingWeight($invoiceItems)
    {
        $itemsTotalWeight = 0;
        foreach ($invoiceItems as $item) {
            $itemsTotalWeight += (float)$item->item->weight * $item->qty;
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

    /**
     * @param $itemsTotalWeight
     * @param $shippingMethod
     * @return float|int
     */
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

    private function finalShippingAmount(int $shippingAmount): float
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
