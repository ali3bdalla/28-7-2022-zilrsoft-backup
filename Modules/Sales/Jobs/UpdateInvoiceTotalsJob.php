<?php

namespace Modules\Sales\Jobs;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
    public function __construct(Invoice $invoice, $expensesAmount = 0)
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
            $result['total'] = (float)$result['total'] + (float)$item->getOriginal('total');
            $result['subtotal'] = (float)$result['subtotal'] + (float)$item->getOriginal('subtotal');
            $result['tax'] = (float)$result['tax'] + (float)$item->getOriginal('tax');
            $result['discount_value'] = $result['discount_value'] + (float)$item->getOriginal('discount');
            $result['net'] = (float)$result['net'] + (float)$item->getOriginal('net');
        }
        $result['net'] = (float)$result['net'] + (float)$this->expensesAmount;
        // dd($result['net']);
        $this->invoice->update($result);

        //
    }
}
