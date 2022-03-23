<?php

namespace App\Jobs\Invoices\Number;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UpdateInvoiceNumberJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private $invoice;
    private $prefix;

    /**
     * Create a new job instance.
     *
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

        if ('ONLINE' == $prefix) {
            $this->prefix = 'ON';
        }
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $nextedInvoiceNumber = DB::table('invoices')->whereYear('created_at', Carbon::now()->format('Y'))->where([
                ['invoice_type', $this->invoice->invoice_type],
                ['organization_id', $this->invoice->organization_id],
            ])->count() + 1;
        $this->invoice->update([
            'invoice_number' => $this->prefix . Carbon::now()->format('Yhs') . Auth::id() . $nextedInvoiceNumber,
        ]);
    }
}
