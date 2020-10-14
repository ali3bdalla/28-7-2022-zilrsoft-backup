<?php

namespace App\Jobs\Items\Price;

use App\Models\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateItemSalesPriceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $invoiceItem;

    private $priceWithTax;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(InvoiceItems $invoiceItem, $priceWithTax = 0)
    {
        //
        $this->priceWithTax = $priceWithTax;
        $this->invoiceItem = $invoiceItem;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $item = $this->invoiceItem->item;
        $priceWithoutTax = (float) $this->priceWithTax / (float) (1 + (float) ($item->vts / 100));
        $item->update([
            'price' => $priceWithoutTax,
            'price_with_tax' => $this->priceWithTax,
        ]);
    }
}
