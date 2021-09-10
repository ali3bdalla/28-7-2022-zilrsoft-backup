<?php

namespace App\Jobs\Invoices\Balance;

use App\Models\Invoice;
use App\Scopes\DraftScope;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateInvoiceBalancesByInvoiceItemsJob implements ShouldQueue
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
        $items = $this->invoice
            ->items()
            ->withoutGlobalScope(DraftScope::class)
            ->where([['is_kit', false]])
            ->get();
        $result['total'] = 0;
        $result['subtotal'] = 0;
        $result['tax'] = 0;
        $result['discount'] = 0;
        $result['net'] = 0;
        $result['remaining'] = 0;
        foreach ($items as $item) {
            $result['total'] = (float)$result['total'] + (float)$item->total;
            $result['subtotal'] = (float)$result['subtotal'] + (float)$item->subtotal;
            $result['tax'] = (float)$result['tax'] + (float)$item->tax;
            $result['discount'] = $result['discount'] + (float)$item->discount;
            $result['net'] = (float)$result['net'] + (float)$item->net;
        }
        $result['net'] = (float)$result['net'] + (float)$this->expensesAmount;

        $this->invoice->update($result);
    }
}
