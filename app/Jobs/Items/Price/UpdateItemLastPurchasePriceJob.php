<?php

namespace App\Jobs\Items\Price;

use App\Models\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateItemLastPurchasePriceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var InvoiceItems
     */
    private $invoiceItem;

    /**
     * Create a new job instance.
     *
     * @param InvoiceItems $invoiceItem
     */
    public function __construct(InvoiceItems $invoiceItem)
    {
        //
        $this->invoiceItem = $invoiceItem;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $this->invoiceItem->item->update([
           'last_p_price'  => $this->price
        ]);
    }
}
