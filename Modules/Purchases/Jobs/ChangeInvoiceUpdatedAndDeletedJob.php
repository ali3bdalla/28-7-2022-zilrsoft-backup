<?php

namespace Modules\Purchases\Jobs;

use App\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChangeInvoiceUpdatedAndDeletedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        //
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $result['is_updated'] = true;
        $result['is_deleted'] = true;
        $items = $this->invoice->items()->where('belong_to_kit', false)->get();
        foreach ($items as $item) {
            if ($item['qty'] > $item['r_qty']) {
                $result['is_deleted'] = false;
            }
        }
        $this->invoice->update($result);
    }
}
