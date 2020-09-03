<?php

namespace Modules\Sales\Jobs;

use App\Invoice;
use App\InvoicePayments;
use App\Payment;
use App\Transaction;
use App\TransactionsContainer;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class DeleteSaleInvoiceJob implements ShouldQueue
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
                
            }else if($this->invoice->invoice_type == 'r_sale')
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
                            $item->item->serials()->where('r_sale_invoice_id', $this->invoice->id)->update([
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
