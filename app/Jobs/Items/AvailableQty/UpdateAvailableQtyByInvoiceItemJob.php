<?php

namespace App\Jobs\Items\AvailableQty;

use App\Models\Invoice;
use App\Models\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateAvailableQtyByInvoiceItemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $invoiceItem;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(InvoiceItems $invoiceItem)
    {
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
        if(in_array($this->invoiceItem->invoice_type,['purchase','return_sale']))
        {
            $availableQtyAfterInvoiceItem =(int)$availableQty + (int)$this->invoiceItem->qty;
        }else
        {
            $availableQtyAfterInvoiceItem =(int)$availableQty - (int)$this->invoiceItem->qty;
        }
        $this->invoiceItem->item()->update([
            'available_qty' => $availableQtyAfterInvoiceItem
        ]);
    }
}
