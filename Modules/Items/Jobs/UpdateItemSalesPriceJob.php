<?php

namespace Modules\Items\Jobs;

use App\Models\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateItemSalesPriceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var InvoiceItems
     */
    private $invoiceItem;
    /**
     * @var int
     */
    private $priceWithTax;

    /**
     * Create a new job instance.
     *
     * @param InvoiceItems $invoiceItem
     * @param int $priceWithTax
     */
    public function __construct(InvoiceItems $invoiceItem, $priceWithTax = 0)
    {
        //
        $this->invoiceItem = $invoiceItem;
        $this->priceWithTax = (float)$priceWithTax;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//        return 1 + $this->vts / 100;
        $priceWithoutTax = $this->priceWithTax / (float)$this->invoiceItem->item->getSaleTaxAsFloatValue();
        $this->invoiceItem->item()->update([
            'price' => $priceWithoutTax,
            'price_with_tax' => $this->priceWithTax
        ]);

    }
}
