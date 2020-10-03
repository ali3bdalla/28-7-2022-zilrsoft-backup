<?php

namespace Modules\Purchases\Jobs;

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

class DeletePurchaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $invoice;
    /**
     * Create a new job instance.
     *
     * @return void
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

            if($this->invoice->invoice_type == 'purchase')
            {

//                 TransactionsContainer::where('invoice_id', $beginning->id)->forceDelete();
//             Transaction::where('invoice_id', $beginning->id)->forceDelete();
//             Payment::where('invoice_id', $beginning->id)->forceDelete();
//             InvoicePayments::where('invoice_id', $beginning->id)->forceDelete();
//             foreach ($beginning->items as $item) {
//                 $current_qty = $item->item->available_qty - $item['qty'];

//                 if (!$item->is_kit) {
//                     $item->item->update([
//                         'available_qty' => $current_qty,
//                     ]);
//                     if ($item->item->is_need_serial) {
//                         $item->item->serials()->where('purchase_invoice_id', $beginning->id)->forceDelete();
//                     }
// //                    $item->item->stockMovement();
//                 }


//             }

//             $beginning->items()->forceDelete();
//             $beginning->forceDelete();
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
                            $item->item->serials()->where('purchase_invoice_id', $this->invoice->id)->forceDelete();
                        }
                    }
    
    
                }
    
                $this->invoice->items()->forceDelete();
                $this->invoice->forceDelete();
                
            }else if($this->invoice->invoice_type == 'return_purchase')
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
                            $item->item->serials()->where('return_purchase_invoice_id', $this->invoice->id)->update([
                                'current_status' => "available"
                            ]);
                        }
                        $item->item->stockMovement();
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
