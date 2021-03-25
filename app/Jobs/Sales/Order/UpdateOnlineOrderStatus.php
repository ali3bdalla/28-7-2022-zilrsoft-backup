<?php

namespace App\Jobs\Sales\Order;

use App\Jobs\Order\Shipping\AutoCreateShippingTransactionJob;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateOnlineOrderStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $draftId;
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * Create a new job instance.
     *
     * @param $draftId
     * @param Invoice $invoice
     */
    public function __construct($draftId, Invoice $invoice)
    {
        //
        $this->draftId = $draftId;
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $draft = Invoice::withoutGlobalScopes(['draft', 'manager', 'accountingPeriod'])->where('id', $this->draftId)->first();

        if ($draft) {
            $order = Order::where([
                ["invoice_id", null],
                ['draft_id', $this->draftId],
                ['status', 'in_progress']
            ])->first();

            if ($order) {
                $order->update(
                    [
                        'status' => 'ready_for_shipping',
                        'invoice_id' => $this->invoice->id
                    ]
                );

                $this->invoice->items()->update(
                    [

                        'is_online' => true
                    ]
                );


                $this->invoice->update(
                    [

                        'is_online' => true
                    ]
                );

                if ($order->shippingMethod && $order->shippingMethod->deliver_when_invoice_created) {
                    AutoCreateShippingTransactionJob::dispatchNow($order);
                }
            }
        }
    }
}
