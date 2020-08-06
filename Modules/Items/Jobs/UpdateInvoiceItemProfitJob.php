<?php

namespace Modules\Items\Jobs;

use App\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateInvoiceItemProfitJob implements ShouldQueue
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
        $profits = $this->invoiceItem->moneyFormatter((float)$this->invoiceItem->price - (float)$this->invoiceItem->cost - (float)$this->invoiceItem->discount);
        if (!$this->invoiceItem->invoice->isSale()) {
            $profits = $profits * -1;
        }
        $this->invoiceItem->update([
            'profit' => $profits
        ]);

    }
}
