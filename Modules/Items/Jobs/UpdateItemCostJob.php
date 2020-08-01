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

        $costBeforeNewRow = $dbItem->moneyFormatter((float)$dbItem->cost);
        $sotckAmountBeforeNewRow = $dbItem->moneyFormatter($costBeforeNewRow * $dbItem->available_qty);
        $availableQty = $dbItem->available_qty;
        $result['cost'] = $costBeforeNewRow;


        if ($this->invoice->isPurchase()){
            $result = $dbItem->handlePurchaseHistory($this->invoiceItem,$sotckAmountBeforeNewRow,$availableQty);
        }else if ($this->invoice->isSale()){
            $result = $dbItem->handleSaleHistory($this->invoiceItem,$costBeforeNewRow,$sotckAmountBeforeNewRow,$availableQty);
        }else if ($this->invoice->isReturnSale()){
            $result = $dbItem->handleReturnSaleHistory($this->invoiceItem,$sotckAmountBeforeNewRow,$costBeforeNewRow,$availableQty);
        }else if ($this->invoice->isReturnPurchase()){
            $result = $dbItem->handleReturnPurchaseHistory($this->invoiceItem,$costBeforeNewRow,$sotckAmountBeforeNewRow,$availableQty);
        }

        $dbItem->update([
            'cost' => $result['cost']
        ]);
    }
}