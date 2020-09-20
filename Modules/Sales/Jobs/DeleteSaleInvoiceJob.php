<?php

namespace Modules\Sales\Jobs;

use App\Models\Invoice;
use App\Models\InvoicePayments;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class DeleteSaleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $invoice;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        
        $this->invoice = $invoice;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try {

            if($this->invoice->invoice_type == 'sale')
            {
                TransactionsContainer::where('invoice_id', $this->invoice->id)->forceDelete();
                Transaction::where('invoice_id', $this->invoice->id)->forceDelete();
                Payment::where('invoice_id', $this->invoice->id)->forceDelete();
                InvoicePayments::where('invoice_id', $this->invoice->id)->forceDelete();
                foreach ($this->invoice->items as $item) {
                    $current_qty = $item->item->available_qty + $item['qty'];
                    if (!$item->is_kit) {
                        $item->item->update([
                            'available_qty' => $current_qty,
                        ]);
                        if ($item->item->is_need_serial) {
                            $item->item->serials()->where('sale_invoice_id', $this->invoice->id)->update([
                                'current_status' => "available",
                                'sale_invoice_id' => 0
                            ]);
                        }
                    }
    
    
                }
    
                $this->invoice->items()->forceDelete();
                $this->invoice->forceDelete();
                
            }else if($this->invoice->invoice_type == 'return_sale')
            {
                TransactionsContainer::where('invoice_id', $this->invoice->id)->forceDelete();
                Transaction::where('invoice_id', $this->invoice->id)->forceDelete();
                Payment::where('invoice_id', $this->invoice->id)->forceDelete();
                InvoicePayments::where('invoice_id', $this->invoice->id)->forceDelete();
                foreach ($this->invoice->items as $item) {
                    $current_qty = $item->item->available_qty - $item['qty'];
                    if (!$item->is_kit) {
                        $item->item->update([
                            'available_qty' => $current_qty,
                        ]);
                        if ($item->item->is_need_serial) {
                            $item->item->serials()->where('return_sale_invoice_id', $this->invoice->id)->update([
                                'current_status' => "saled"
                            ]);
                        }
//                        $item->item->stockMovement();
                    }
    
    
                }
    
                $this->invoice->items()->forceDelete();
                $this->invoice->forceDelete();
            }
            

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
        }
    }
}
