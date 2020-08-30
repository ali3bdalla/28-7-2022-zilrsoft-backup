<?php

namespace Modules\Purchases\Jobs;

use App\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateInvoiceTotalsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Invoice
     */
    private $invoice;
    /**
     * @var int
     */
    private $expensesAmount;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     * @param int $expensesAmount
     */
    public function __construct(Invoice $invoice,$expensesAmount = 0)
    {
        //
        $this->invoice = $invoice;
        $this->expensesAmount = (float)$expensesAmount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $children = $this->invoice
            ->items()
            ->where([['belong_to_kit', false]])
            ->get();
        $result['total'] = 0;
        $result['subtotal'] = 0;
        $result['tax'] = 0;
        $result['discount_value'] = 0;
        $result['net'] = 0;
        $result['remaining'] = 0;
        $items = $children;
        foreach ($items as $item) {
            $result['total'] = (float)$result['total'] + $item->getOriginal('total');
            $result['subtotal'] = (float)$result['subtotal'] + $item->getOriginal('subtotal');
            $result['tax'] = (float)$result['tax'] + $item->getOriginal('tax');
            $result['discount_value'] = $result['discount_value'] + $item->getOriginal('discount');
            $result['net'] = (float)$result['net'] + $item->getOriginal('net');
        }
        $result['net'] = (float)$result['net'] + (float)$this->expensesAmount;
        $this->invoice->update($result);
//
    }
}
