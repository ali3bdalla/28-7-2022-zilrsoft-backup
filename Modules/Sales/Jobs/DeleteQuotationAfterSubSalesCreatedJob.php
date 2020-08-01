<?php

namespace Modules\Sales\Jobs;

use App\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteQuotationAfterSubSalesCreatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var int
     */
    private $quotationId;

    /**
     * Create a new job instance.
     *
     * @param int $quotationId
     */
    public function __construct($quotationId = 0)
    {
        //
        $this->quotationId = $quotationId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $quotation = Invoice::find($this->quotationId);
        if (!empty($quotation)) {
            $quotation->update([
                'is_deleted' => true
            ]);
        }
    }
}
