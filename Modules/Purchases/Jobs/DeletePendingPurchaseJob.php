<?php

namespace Modules\Purchases\Jobs;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeletePendingPurchaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var int
     */
    private $pendingPurchaseId;

    /**
     * Create a new job instance.
     *
     * @param int $pendingPurchaseId
     */
    public function __construct($pendingPurchaseId = 0)
    {
        //
        $this->pendingPurchaseId = $pendingPurchaseId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->pendingPurchaseId > 0) {
            $invoice = Invoice::find($this->pendingPurchaseId);
            if ($invoice)
                $invoice->delete();
        }
    }
}
