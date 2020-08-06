<?php

namespace Modules\Items\Jobs;

use App\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateItemReturnedQtyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var InvoiceItems
     */
    private $invoiceItem;
    private $returnedQty;

    /**
     * Create a new job instance.
     *
     * @param InvoiceItems $invoiceItem
     * @param $returnedQty
     */
    public function __construct(InvoiceItems $invoiceItem, $returnedQty)
    {
        //
        $this->invoiceItem = $invoiceItem;
        $this->returnedQty = $returnedQty;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->invoiceItem->fresh()->update([
            'r_qty' =>(int)$this->invoiceItem->r_qty + (int)$this->returnedQty
        ]);

    }
}
