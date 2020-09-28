<?php

namespace App\Jobs\Items\Profit;

use App\Models\Invoice;
use App\Models\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateItemProfitByInvoiceItem implements ShouldQueue
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
        $totalProfitAmount = $this->invoiceItem->item->total_profits_amount;

        if($this->invoiceItem->item->is_service)
        {

            $profitAmount = $this->invoiceItem->subtotal;
            if ($this->invoiceItem->invoice_type == 'sale') {
                $totalProfitAmount += $profitAmount;
            }

            if ($this->invoiceItem->invoice_type == 'return_sale') {
                $totalProfitAmount -= $profitAmount;
                $profitAmount = ($profitAmount * -1);
            }

        }else
        {
            $profitAmount = ($this->invoiceItem->subtotal - ($this->invoiceItem->item->cost * $this->invoiceItem->qty));
            if ($this->invoiceItem->invoice_type == 'sale') {
                $totalProfitAmount += $profitAmount;
            }

            if ($this->invoiceItem->invoice_type == 'return_sale') {
                $totalProfitAmount -= $profitAmount;
                $profitAmount = ($profitAmount * -1);
            }
        }


        $this->invoiceItem->item()->update([
            'total_profits_amount' => $totalProfitAmount
        ]);
        $this->invoiceItem->update([
            'profit' => $profitAmount
        ]);

    }
}
