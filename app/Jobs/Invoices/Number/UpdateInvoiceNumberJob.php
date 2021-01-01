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
    public function __construct(Invoice $invoice, $prefix = 'P')
    {
        $this->invoice = $invoice;
        switch ($invoice->invoice_type) {
            case 'purchase':
                $this->prefix = 'P';
                break;

            case 'return_purchase':
                $this->prefix = 'RP';
                break;

            case 'sale':
                $this->prefix = 'S';
                break;

            case 'return_sale':
                $this->prefix = 'RS';
                break;

            case 'beginning_inventory':
                $this->prefix = 'BG';
                break;  
            case 'stock_adjustment':
                $this->prefix = 'AD';
                break;  
            default:
                $this->prefix = $prefix;
                break;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $nextedInvoiceNumber = (Invoice::whereYear('created_at', Carbon::now()->format('Y'))->where('invoice_type', $this->invoice->type)->withTrashed()->withoutGlobalScopes(["manager", 'draft'])->count() + 1);
        $this->invoice->update([
            'invoice_number' => $this->prefix . Carbon::now()->format('Y') . $nextedInvoiceNumber
        ]);
    }
}
