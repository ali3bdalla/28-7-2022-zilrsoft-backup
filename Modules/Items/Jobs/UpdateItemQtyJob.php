<?php

namespace Modules\Items\Jobs;

use App\Invoice;
use App\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateItemQtyJob implements ShouldQueue
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
        $availableQty = $this->invoiceItem->item->available_qty;
        $newAvailableQty = $availableQty;

        if (!$this->invoice->isPending()) {
            if (in_array($this->invoice->invoice_type, ['purchase', 'beginning_inventory', 'r_sale'])) $newAvailableQty = $availableQty + $this->invoiceItem->qty;
            else $newAvailableQty = $availableQty - (int)$this->invoiceItem->qty;
        }




        $this->invoiceItem->item()->update([
            'available_qty' => $newAvailableQty
        ]);

//        dd($availableQty,$this->invoiceItem->qty,$newAvailableQty,$this->invoiceItem->item->fresh()->available_qty,$this->invoiceItem->item->barcode,$this->invoiceItem->item->id);

    }
}
