<?php

namespace App\Console\Commands;

use App\Account;
use App\Invoice;
use App\InvoiceItems;
use App\Item;
use App\Transaction;
use App\TransactionsContainer;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Accounting\Jobs\CreatePurchasesEntityTransactionsJob;
use Modules\Purchases\Jobs\CreatePurchaseItemsJob;
use Modules\Purchases\Jobs\DeletePurchaseInvoiceJob;
use Modules\Purchases\Jobs\DeleteSalesInoviceJob;
use Modules\Purchases\Jobs\EnsurePurchaseDataAreCorrectJob;
use Modules\Sales\Jobs\UpdateInvoiceTotalsJob;

class PurchasesInvoicesTransactionFixerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:purchases_invoices_transactions_fixer_command';

    private $itemsUpdated = [];
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        DB::beginTransaction();
        try {
            $this->fixPurchases();
            $this->fixReturnPurchases();
            // Item::whereIn('id', $this->itemsUpdated)->update([
            //     'vtp' => 15
            // ]);
            DB::commit();
            // DB::rollBack();
        } catch (QueryException $e) {
            DB::rollBack();
            throw $e;
        } catch (ValidationException $e) {
            DB::rollBack();
            dd($e->getTrace());
            throw $e;
        }
    }




    private function fixPurchases()
    {

        foreach (Invoice::whereIn('invoice_type', ['purchase'])->get() as $invoice) {
            // auth()->loginUsingId($invoice->creator_id);
            $creditAmount = 0;
            $debitAmount = 0;
           
            foreach ($invoice->transactions()->where('description', '!=', 'vendor_balance')->get()  as $transaction) {

                if (in_array($transaction['description'], ['to_tax', 'to_item'])) {
                    $creditAmount = $creditAmount + $transaction['amount'];
                } else {
                    $debitAmount = $debitAmount + $transaction['amount'];
                }
            }


            $diff =  $creditAmount  - $debitAmount;
            if ($creditAmount == 0 || $debitAmount == 0 ||  abs($diff) > 1) {
                echo $invoice->id. "\n";
                
                dispatch(new DeletePurchaseInvoiceJob($invoice));
                continue;
            }

            // if ($creditAmount != $debitAmount) {


            // if(abs($creditAmount - $debitAmount) > 5)
            // dispatch(new DeletePurchaseInvoiceJob($invoice));
            // } else {
            //     $items = [];

            //     $pureItems = $invoice->items()->where('belong_to_kit', false)->get();
            //     foreach ($pureItems as $item) {
            //         if ($item->item != null) {
            //             $base['id'] = $item->item_id;
            //             $base['purchase_price'] = $item->price;
            //             $base['qty'] = $item->qty;
            //             $base['discount'] = $item->discount;

            //             if ($item->is_kit) {

            //                 $childItems = [];
            //                 foreach ($invoice->items()->where([
            //                     ['belong_to_kit', true],
            //                     ['parent_kit_id', $item->id],
            //                 ])->get() as $kitItem) {
            //                     $base2['id'] = $kitItem->item_id;
            //                     if ($kitItem->item->is_need_serial) {
            //                         $base2['serials'] = $kitItem->item->serials()->where([
            //                             ['purchase_invoice_id', $invoice->id]
            //                         ])->pluck('serial')->toArray();
            //                     }
            //                     $childItems[] = $base2;
            //                     $this->updateSalesItem($kitItem, $invoice);
            //                 }
            //                 $base['items'] = $childItems;
            //                 // dd($base);
            //             } else {


            //                 if ($item->item->is_need_serial) {
            //                     $serials = $item->item->serials()->where(
            //                         [
            //                             ['purchase_invoice_id', $invoice->id],
            //                         ]
            //                     )->pluck('serial')->toArray();
            //                     // dd($serials);
            //                     $base['serials'] = $serials;
            //                 }

            //                 $this->updatePurchaseItem($item, $invoice);
            //             }
            //             $items[] = $base;
            //         }
            //     }


            //     $expenses = [];
            //     foreach ($invoice->expenses()->with('expense')->get() as $expense) {
            //         $expenses[] = [
            //             'id' => $expense['expense_id'],
            //             'is_open' => true,
            //             'is_apended_to_net' => $expense['with_net'],
            //             'amount' => $expense['amount']
            //         ];
            //     }

            //     $payments = [];
            //     foreach ($invoice->payments as $payment) {
            //         // dd($payment);
            //         $paymentable = $payment->paymentable;
            //         $paymentable->amount = $payment->amount;
            //         $payments[] = $paymentable->toArray();
            //     }
            //     $expensesAmount = (float)collect($expenses)->sum('amount');
            //     $invoice->items()->forceDelete();
            //     TransactionsContainer::where('invoice_id', $invoice->id)->forceDelete();
            //     Transaction::where('invoice_id', $invoice->id)->forceDelete();
            //     $invoice->payments()->forceDelete();
            //     $invoice->expenses()->forceDelete();

            //     $transactionContaniner = $this->createTransactionContainer($invoice);
            //     dispatch(new CreatePurchaseItemsJob($transactionContaniner,$invoice, $items, $payments, $expenses));
            //     dispatch(new UpdateInvoiceTotalsJob($invoice, $expensesAmount));
            //     dispatch(new CreatePurchasesEntityTransactionsJob($transactionContaniner,$invoice, $payments, $expenses,  $items));
            //     dispatch(new EnsurePurchaseDataAreCorrectJob($invoice));
            // }
            // }
        }
    }



    private function fixReturnPurchases()
    {
        //1090 , 13036, 13037, 13038, 13039, 13063, 13074, 14032, 13334, 14134, 14664, 15058
        foreach (Invoice::whereIn('invoice_type', ['r_purchase'])->get()  as $invoice) {
            $parentInvoice = Invoice::find($invoice->parent_invoice_id);
            // auth()->loginUsingId($invoice->user_id);
            $creditAmount = 0;
            $debitAmount = 0;
            foreach ($invoice->transactions()->where('description', '!=', 'client_balance')->get()  as $transaction) {
                if (in_array($transaction['description'], [
                    'to_cogs', 'to_gateway',
                    'to_products_sales_discount', 'to_services_sales_discount',
                    'to_other_services_sales_discount', 'to_stock'
                ])) {
                    $creditAmount = $creditAmount + $transaction['amount'];
                } else {
                    $debitAmount = $debitAmount + $transaction['amount'];
                }
            }

            // if ($creditAmount != $debitAmount) {
            //     // dd( $creditAmount == 0 || $debitAmount == 0 || $parentInvoice == null);
            //     if ($creditAmount == 0 || $debitAmount == 0 || $parentInvoice == null) {

            $diff =  $creditAmount  - $debitAmount;
            if ($creditAmount == 0 || $debitAmount == 0 || $parentInvoice  == null || abs($diff) > 1) {
                echo $invoice->id. "\n";

                dispatch(new DeletePurchaseInvoiceJob($invoice));
                continue;
            }            // } else {
            //     $items = [];
            //     foreach ($invoice->items as $item) {
            //         if ($item->item != null) {

            //             $parentInvoiceItem = $parentInvoice->items()->where('item_id', $item->item_id)->first();
            //             $base['id'] = $parentInvoiceItem->id;
            //             $base['price'] = $item->price;
            //             $base['returned_qty'] = $item->qty;
            //             $base['discount'] = $item->discount;


            //             if ($item->is_kit) {
            //                 $childItems = [];
            //                 foreach ($invoice->items()->where([
            //                     ['belong_to_kit', true],
            //                     ['parent_kit_id', $item->id],
            //                 ])->get() as $kitItem) {
            //                     $parentKitItemInvoiceItem = $parentInvoice->items()->where('item_id', $kitItem->item_id)->first();
            //                     $base2['id'] = $parentKitItemInvoiceItem->id;

            //                     if ($kitItem->item->is_need_serial) {
            //                         $base2['serials'] = $kitItem->item->serials()->where([
            //                             ['r_sale_invoice_id', $invoice->id],
            //                             ['current_status', 'r_sale']
            //                         ])->pluck('serial')->toArray();
            //                     }
            //                     $childItems[] = $base2;
            //                     $this->updateReturnSalesItem($parentKitItemInvoiceItem, $invoice);
            //                 }
            //                 $base['items'] = $childItems;
            //             } else {

            //                 if ($item->item->is_need_serial) {
            //                     $base['serials'] = $item->item->serials()->where(
            //                         [
            //                             ['r_sale_invoice_id', $invoice->id],
            //                             ['current_status', 'r_sale']
            //                         ]
            //                     )->pluck('serial')->toArray();
            //                 }
            //                 $this->updateReturnSalesItem($parentInvoiceItem, $invoice);

            //                 // dd($item->r_qty);
            //             }


            //             $items[] = $base;
            //         }
            //     }



            //     $payments = [];
            //     foreach ($invoice->payments as $payment) {
            //         $paymentable = $payment->paymentable;
            //         $paymentable->amount = $payment->amount;
            //         $payments[] = $paymentable->toArray();
            //     }


            //     if ($invoice->user()->is_system_user && $payments === []) {
            //         $payment = Account::where([
            //             ['is_system_account', true],
            //             ['slug', 'temp_reseller_account'],
            //         ])->first();
            //         $payment->amount = $invoice->net;

            //         $payments[] = $payment->toArray();
            //     }



            //     $invoice->items()->forceDelete();
            //     TransactionsContainer::where('invoice_id', $invoice->id)->forceDelete();
            //     Transaction::where('invoice_id', $invoice->id)->forceDelete();
            //     $invoice->payments()->forceDelete();
            //     $transactionContaniner = $this->createTransactionContainer($invoice);
            //     dispatch(new CreateReturnSalesItemsJob($transactionContaniner, $invoice, $items, $parentInvoice));
            //     dispatch(new UpdateInvoiceTotalsJob($invoice));
            //     dispatch(new CreateReturnSalesEntityTransactionsJob($transactionContaniner, $invoice, $payments));
            //     dispatch(new EnsureReturnSalesDataAreCorrectJob($invoice));
            // dd($transactionContaniner);
        }
    }








    // private function createTransactionContainer($invoice)
    // {

    //     $transactionContaniner = new TransactionsContainer(
    //         [
    //             'creator_id' => $invoice->creator_id,
    //             'organization_id' => $invoice->organization_id,
    //             'invoice_id' => $invoice->id,
    //             'amount' => 0,
    //             'description' => 'invoice'
    //         ]
    //     );
    //     $transactionContaniner->save();

    //     return $transactionContaniner;
    // }



    // private function updatePurchaseItem(InvoiceItems $item, Invoice $invoice)
    // {

    //     $currentQty = $item->item->available_qty;
    //     if ($currentQty < 0) {
    //         $currentQty = 0;
    //     }

    //     $changedQty = $item->qty;
    //     if ($item->belong_to_kit) {
    //         // dd($item->parent_kit_id);
    //         $changedQty = (InvoiceItems::find($item->parent_kit_id))->item->items()->where('item_id', $item->item_id)->first()->qty;
    //     }

    //     $current_qty = $currentQty - $changedQty;
    //     if (!$item->is_kit) {
    //         $item->item->update([
    //             'available_qty' => $current_qty
    //             // 'cost' => $item->cost
    //         ]);
    //         if ($item->item->is_need_serial) {
    //             $item->item->serials()->where([
    //                 ['purchase_invoice_id', $invoice->id],
    //             ])->delete();
    //         }
    //     }

    //     $this->changevtpTo5($item->item);
    // }


    // private function updateReturnSalesItem(InvoiceItems $item, Invoice $invoice)
    // {

    //     $current_qty = $item->item->available_qty - $item->r_qty;

    //     if ($current_qty < 0) {
    //         $current_qty = 0;
    //     }

    //     $item->update([
    //         'r_qty' => 0
    //     ]);




    //     // if()

    //     // dd($item->r_qty);
    //     if (!$item->is_kit) {


    //         $item->item->update([
    //             'available_qty' => $current_qty,
    //         ]);
    //         if ($item->item->is_need_serial) {
    //             $item->item->serials()->where('r_sale_invoice_id', $invoice->id)->update([
    //                 'current_status' => "saled",
    //                 'r_sale_invoice_id' => 0
    //             ]);
    //         }
    //     }

    //     $this->changevtpTo5($item->item);
    // }


    // private function changevtpTo5(Item $item)
    // {

    //     $item->update([
    //         'vtp' => 5
    //     ]);

    //     // dd($item->fresh()->toArray());

    //     $this->itemsUpdated[] = $item->id;
    // }
}
