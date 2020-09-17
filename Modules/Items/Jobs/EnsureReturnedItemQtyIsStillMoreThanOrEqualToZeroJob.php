<?php

namespace Modules\Items\Jobs;

use App\Models\InvoiceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EnsureReturnedItemQtyIsStillMoreThanOrEqualToZeroJob implements ShouldQueue
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
        //
        if($this->invoiceItem->r_qty < 0)
        {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                "items.{$this->index}.qty"=> ['item qty can\'t be less than zero '],
            ]);
            throw $error;
        }
    }
}
