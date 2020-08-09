<?php

namespace Modules\Items\Jobs;

use App\Invoice;
use App\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Database\QueryException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateItemCostJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Invoice
     */
    private $invoice;
    /**
     * @var InvoiceItems
     */
    private $invoiceItem;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     * @param InvoiceItems $invoiceItem
     */
    public function __construct(Invoice $invoice,InvoiceItems $invoiceItem)
    {
        //

        $this->invoice = $invoice;
        $this->invoiceItem = $invoiceItem;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dbItem = $this->invoiceItem->item;
        $costBeforeNewRow = (float)$dbItem->cost;
        $stockAmountBeforeNewRow = $dbItem->moneyFormatter($costBeforeNewRow * (int)$dbItem->available_qty);


        $availableQty = $dbItem->available_qty;
        $result['cost'] = $costBeforeNewRow;

        if ($this->invoice->isPurchase()){
            $result = $dbItem->handlePurchaseHistory($this->invoiceItem,$stockAmountBeforeNewRow,$availableQty);
        }else if ($this->invoice->isSale()){
            $result = $dbItem->handleSaleHistory($this->invoiceItem,$costBeforeNewRow,$stockAmountBeforeNewRow,$availableQty);
        }else if ($this->invoice->isReturnSale()){
            $result = $dbItem->handleReturnSaleHistory($this->invoiceItem,$stockAmountBeforeNewRow,$costBeforeNewRow,$availableQty);
        }else if ($this->invoice->isReturnPurchase()){
            $result = $dbItem->handleReturnPurchaseHistory($this->invoiceItem,$costBeforeNewRow,$stockAmountBeforeNewRow,$availableQty);
        }

//        dd($result['cost']);

        $dbItem->update([
            'cost' => $result['cost']
        ]);
    }
}
