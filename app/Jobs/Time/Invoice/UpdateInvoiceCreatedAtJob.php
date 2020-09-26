<?php

namespace App\Jobs\Time\Invoice;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateInvoiceCreatedAtJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $invoiceId;
    private $createdAt;

    /**
     * Create a new job instance.
     *
     * @param $invoiceId
     * @param $createdAt
     */
    public function __construct($invoiceId, $createdAt)
    {
        //
        $this->invoiceId = $invoiceId;
        $this->createdAt = $createdAt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $invoice = Invoice::find($this->invoiceId);
        $invoice->update([
            'created_at' => $this->createdAt
        ]);


        foreach ($invoice->items as $item) {
            $item->update([
                'created_at' => $this->createdAt
            ]);

            if (in_array($invoice->invoice_type, ['purchase', 'beginning_inventory'])) {
                $item->item->serials()->where([
                    'purchase_id' => $invoice->id
                ])->update([
                    'created_at' => $this->createdAt
                ]);
            }


            TransactionsContainer::where('invoice_id', $invoice->id)->update([
                'created_at' => $this->createdAt

            ]);
            Transaction::where('invoice_id', $invoice->id)->update([
                'created_at' => $this->createdAt

            ]);

            Payment::where('invoice_id', $invoice->id)->update([
                'created_at' => $this->createdAt

            ]);
        }

    }
}
