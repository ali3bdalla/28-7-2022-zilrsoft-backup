<?php

namespace App\Jobs\Invoices\Number;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateInvoiceNumberJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $invoice, $prefix;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     * @param string $prefix
     */
    public function __construct(Invoice $invoice, $prefix = 'PU-')
    {
        $this->invoice = $invoice;
        $this->prefix = $prefix;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->invoice->update([
            'invoice_number' => Carbon::now()->format('Y') . '/' .$this->prefix . $this->invoice->id
        ]);
    }
}
