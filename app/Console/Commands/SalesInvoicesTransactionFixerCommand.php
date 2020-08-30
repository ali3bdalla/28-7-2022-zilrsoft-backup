<?php

namespace App\Console\Commands;

use App\Account;
use App\Invoice;
use App\InvoiceItems;
use App\Item;
use App\Transaction;
use App\TransactionsContainer;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Accounting\Jobs\CreateReturnSalesEntityTransactionsJob;
use Modules\Accounting\Jobs\CreateSalesEntityTransactionsJob;
use Modules\Sales\Jobs\CreateReturnSalesItemsJob;
use Modules\Sales\Jobs\CreateSalesItemsJob;
use Modules\Sales\Jobs\DeleteSaleInvoiceJob;
use Modules\Sales\Jobs\EnsureReturnSalesDataAreCorrectJob;
use Modules\Sales\Jobs\EnsureSalesDataAreCorrectJob;
use Modules\Sales\Jobs\UpdateInvoiceTotalsJob;

class SalesInvoicesTransactionFixerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sales_invoices_transactions_fixer_command';

    private $changedItemsVtsIds = [];
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

        $activeInvoice = 0;
        DB::beginTransaction();
        try {
            // $this->fixSales();
            // $this->fixReturnSales();
            // Item::whereIn('id', $this->itemsUpdated)->update([
            //     'vts' => 15
            // ]);

            $escapeInvoice = [474,477];

            $invoices = Invoice::whereNotIn('id',   $escapeInvoice)->where('invoice_type', 'sale')->get();
            // $invoices = Invoice::find([]);
            foreach ($invoices as $invoice) {
                $activeInvoice = $invoice->id;
                auth()->loginUsingId($invoice->creator_id);
                $creditAmount = 0;
                $debitAmount = 0;
                foreach ($invoice->transactions()->where('description', '!=', 'client_balance')->get()  as $transaction) {
                    if (!in_array($transaction['description'], [
                        'to_cogs', 'to_gateway',
                        'to_products_sales_discount', 'to_services_sales_discount',
                        'to_other_services_sales_discount', 'to_stock'
                    ])) {
                        $creditAmount = $creditAmount + $transaction['amount'];
                    } else {
                        $debitAmount = $debitAmount + $transaction['amount'];
                    }
                }
                

                if ($creditAmount == 0 || $debitAmount == 0) {
                    dispatch(new DeleteSaleInvoiceJob($invoice));
                    continue;
                }


                $def = (float)$creditAmount - (float)$debitAmount;
                if (abs($def) > 1) {
                    echo $invoice->id."\n";
                    $items = $this->fetchItems($invoice);
                    if ($items == null) {
                        dispatch(new DeleteSaleInvoiceJob($invoice));
                        continue;
                    }
                    $payments = $this->fetchPayments($invoice);
                    $this->deleteMasDetails($invoice);
                    $this->createNewInvoice($invoice, $items, $payments);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getTrace(),$activeInvoice);
            throw $e;
        }
    }


    public function fetchItems(Invoice $invoice)
    {

        $items = [];
        $itemsAndKitsDB = $invoice->items()->where('belong_to_kit', false)->get();
        foreach ($itemsAndKitsDB as $invoiceItem) {
            if ($invoiceItem == null || $invoiceItem->item == null) {
                $items = null;
                break;
            }

            $newInvoiceItem = [];
            $newInvoiceItem['id'] = $invoiceItem->item_id;
            $newInvoiceItem['price'] = $invoiceItem->price;
            $newInvoiceItem['qty'] = $invoiceItem->qty;
            $newInvoiceItem['discount'] = $invoiceItem->discount;

            if ($invoiceItem->is_kit) {
                $kitItemsArray = [];
                foreach ($invoice->items()->where([
                    ['belong_to_kit', true],
                    ['parent_kit_id', $invoiceItem->id],
                ])->get() as $invoiceKitItem) {
                    $newInvoiceKitItem['id'] = $invoiceKitItem->item_id;
                    if ($invoiceKitItem->item->is_need_serial) {
                        $newInvoiceKitItem['serials'] = $invoiceKitItem->item->serials()->where([
                            ['sale_invoice_id', $invoice->id],
                            ['current_status', 'saled']
                        ])->pluck('serial')->toArray();
                    }
                    $kitItemsArray[] = $newInvoiceKitItem;
                    $this->changetItemQtyAndDetails($invoiceKitItem, $invoice);
                }
                $newInvoiceItem['items'] = $kitItemsArray;
            } else {
                if ($invoiceItem->item->is_need_serial) {
                    $serials = $invoiceItem->item->serials()->where(
                        [
                            ['sale_invoice_id', $invoice->id],
                            ['current_status', 'saled']
                        ]
                    )->pluck('serial')->toArray();
                    // dd($serials);
                    $newInvoiceItem['serials'] = $serials;
                }

                $this->changetItemQtyAndDetails($invoiceItem, $invoice);
            }
            $items[] = $newInvoiceItem;
        }

        return $items;
    }



    private function changetItemQtyAndDetails(InvoiceItems $invoiceItem, Invoice $invoice)
    {

        $currentQty = $invoiceItem->item->available_qty;
        if ($currentQty < 0) {
            $currentQty = 0;
        }

        $changedQty = $invoiceItem->qty;
        if ($invoiceItem->belong_to_kit) {
            $changedQty = (InvoiceItems::find($invoiceItem->parent_kit_id))->item->items()->where('item_id', $invoiceItem->item_id)->first()->qty;
        }

        $current_qty = $currentQty + $changedQty;
        if (!$invoiceItem->is_kit) {
            $invoiceItem->item->update([
                'available_qty' => $current_qty,
            ]);
            if ($invoiceItem->item->is_need_serial) {
                $invoiceItem->item->serials()->where([
                    ['sale_invoice_id', $invoice->id],
                    ['current_status', 'saled']
                ])->update([
                    'current_status' => "available",
                    'sale_invoice_id' => 0
                ]);
            }
        }

        if (Carbon::parse($invoice->created_at)->lte(Carbon::parse('30-07-2020'))) {
            $invoiceItem->item()->update([
                'vts' => 5
            ]);

            $this->changedItemsVtsIds[] = $invoiceItem->item_id;
        }
    }




    public function fetchPayments(Invoice $invoice)
    {

        $payments = [];
        // 
        foreach ($invoice->payments as $payment) {
            $paymentable = $payment->paymentable;
            $paymentable->amount = $payment->amount;
            $payments[] = $paymentable->toArray();
        }

        
        return $payments;
    }




    public function insureThereIsPaymentsForSystemUser(Invoice $invoice,$payments = []){
        // $totalPayments = collect()
        if ($invoice->user()->is_system_user && $payments === []) {
            $payment = Account::where([
                ['is_system_account', true],
                ['slug', 'temp_reseller_account'],
            ])->first();
            $payment->amount = $invoice->net;
            $payments[] = $payment->toArray();
        }

        return $payments;
    }


    public function deleteMasDetails(Invoice $invoice)
    {
        $invoice->items()->forceDelete();
        TransactionsContainer::where('invoice_id', $invoice->id)->forceDelete();
        Transaction::where('invoice_id', $invoice->id)->forceDelete();
        $invoice->payments()->forceDelete();
    }



    public function createNewInvoice(Invoice $invoice, $items, $payments)
    {
        $entity = $this->createEntity($invoice);
        dispatch(new CreateSalesItemsJob($entity, $invoice, $items));
        dispatch(new UpdateInvoiceTotalsJob($invoice));
        $payments = $this->insureThereIsPaymentsForSystemUser($invoice,$payments);
        dispatch(new CreateSalesEntityTransactionsJob($entity, $invoice, $payments));
        dispatch(new EnsureSalesDataAreCorrectJob($invoice));
    }




    private function createEntity($invoice)
    {

        $transactionContaniner = new TransactionsContainer(
            [
                'creator_id' => $invoice->creator_id,
                'organization_id' => $invoice->organization_id,
                'invoice_id' => $invoice->id,
                'amount' => 0,
                'description' => 'invoice'
            ]
        );
        $transactionContaniner->save();
        return $transactionContaniner;
    }



    // private function fixSales()
    // {
    // 9954, 12784, 13033, 13034, 13035, 13040, 13041, 13042, 13043, 13044,13045, 13046, 13047,
    // 13048, 13049, 13050, 13051, 13052, 13053, 13054, 13058, 13059, 13060,
    //  13061, 13062, 13064
    // foreach (Invoice::where('invoice_type', 'sale')->get() as $invoice) {
    //     // dd($invoice);
    //     // auth()->loginUsingId($invoice->creator_id);
    //     $creditAmount = 0;
    //     $debitAmount = 0;
    //     foreach ($invoice->transactions()->where('description', '!=', 'client_balance')->get()  as $transaction) {
    //         if (!in_array($transaction['description'], [
    //             'to_cogs', 'to_gateway',
    //             'to_products_sales_discount', 'to_services_sales_discount',
    //             'to_other_services_sales_discount', 'to_stock'
    //         ])) {
    //             $creditAmount = $creditAmount + $transaction['amount'];
    //         } else {
    //             $debitAmount = $debitAmount + $transaction['amount'];
    //         }
    //     }
    //     if ($creditAmount == 0 || $debitAmount == 0) {
    //         dispatch(new DeleteSaleInvoiceJob($invoice));
    //         continue;
    //     }
    //     $diff =  $creditAmount  - $debitAmount;
    //     if($diff !== 0)
    //     {
    //         $this->
    //     }

    // if ($creditAmount != $debitAmount) {
    // if ($creditAmount == 0 || $debitAmount == 0) {
    //     dispatch(new DeleteSaleInvoiceJob($invoice));
    // } else {
    // $items = [];

    // $pureItems = $invoice->items()->where('belong_to_kit', false)->get();
    // foreach ($pureItems as $item) {
    //     if ($item->item != null) {
    //         $base['id'] = $item->item_id;
    //         $base['price'] = $item->price;
    //         $base['qty'] = $item->qty;
    //         $base['discount'] = $item->discount;

    //         if ($item->is_kit) {

    //             $childItems = [];
    //             foreach ($invoice->items()->where([
    //                 ['belong_to_kit', true],
    //                 ['parent_kit_id', $item->id],
    //             ])->get() as $kitItem) {
    //                 $base2['id'] = $kitItem->item_id;
    //                 if ($kitItem->item->is_need_serial) {
    //                     $base2['serials'] = $kitItem->item->serials()->where([
    //                         ['sale_invoice_id', $invoice->id],
    //                         ['current_status', 'saled']
    //                     ])->pluck('serial')->toArray();
    //                 }
    //                 $childItems[] = $base2;
    //                 $this->updateSalesItem($kitItem, $invoice);
    //             }
    //             $base['items'] = $childItems;
    //             // dd($base);
    //         } else {


    //             if ($item->item->is_need_serial) {
    //                 $serials = $item->item->serials()->where(
    //                     [
    //                         ['sale_invoice_id', $invoice->id],
    //                         ['current_status', 'saled']
    //                     ]
    //                 )->pluck('serial')->toArray();
    //                 // dd($serials);
    //                 $base['serials'] = $serials;
    //             }

    //             $this->updateSalesItem($item, $invoice);
    //         }
    //         $items[] = $base;
    //     }
    // }


    // // if($invoice->id == 13040)
    // // {
    // //     dd($items);
    // // }


    // // 
    // $payments = [];
    // // 
    // foreach ($invoice->payments as $payment) {
    //     // dd($payment);
    //     $paymentable = $payment->paymentable;
    //     $paymentable->amount = $payment->amount;
    //     $payments[] = $paymentable->toArray();
    // }




    // // dd($items);
    // $invoice->items()->forceDelete();
    // TransactionsContainer::where('invoice_id', $invoice->id)->forceDelete();
    // Transaction::where('invoice_id', $invoice->id)->forceDelete();
    // $invoice->payments()->forceDelete();
    // // // delete
    // $transactionContaniner = $this->createTransactionContainer($invoice);
    // dispatch(new CreateSalesItemsJob($transactionContaniner, $invoice, $items));
    // dispatch(new UpdateInvoiceTotalsJob($invoice));
    // if ($invoice->user()->is_system_user && $payments === []) {
    //     $payment = Account::where([
    //         ['is_system_account', true],
    //         ['slug', 'temp_reseller_account'],
    //     ])->first();
    //     $payment->amount = $invoice->net;

    //     $payments[] = $payment->toArray();
    // }

    // dispatch(new CreateSalesEntityTransactionsJob($transactionContaniner, $invoice, $payments));
    // dispatch(new EnsureSalesDataAreCorrectJob($invoice));
    // echo $invoice->id . "\n";
    // dd($transactionContaniner);
    // }
    // }

    // $diffenet = $creditAmount + $debitAmount;
    // echo $creditAmount . "\n";
    // echo $debitAmount;

    // }
    // }
    // 



    // private function fixReturnSales()
    // {
    //     // $account = Account::find(24);
    //     //1090 , 13036, 13037, 13038, 13039, 13063, 13074, 14032, 13334, 14134, 14664, 15058
    //     foreach (Invoice::where('invoice_type', 'r_sale')->get() as $invoice) {
    //         $parentInvoice = Invoice::find($invoice->parent_invoice_id);
    //         auth()->login($invoice->creator);
    //         $creditAmount = 0;
    //         $debitAmount = 0;

    //         foreach ($invoice->transactions()->where('description', '!=', 'client_balance')->get()  as $transaction) {
    //             if (in_array($transaction['description'], [
    //                 'to_cogs', 'to_gateway',
    //                 'to_products_sales_discount', 'to_services_sales_discount',
    //                 'to_other_services_sales_discount', 'to_stock'
    //             ])) {
    //                 $creditAmount = $creditAmount + $transaction['amount'];
    //             } else {
    //                 $debitAmount = $debitAmount + $transaction['amount'];
    //             }
    //         }
    //         $diff =  $creditAmount  - $debitAmount;
    //         if ($creditAmount == 0 || $debitAmount == 0 || $parentInvoice  == null) {
    //             dispatch(new DeleteSaleInvoiceJob($invoice));
    //             continue;
    //         }
    //         if($diff !== 0)
    //         {

    //         }

    //         // else if ($creditAmount > $debitAmount) {
    //         //         $variation = $creditAmount - $debitAmount;

    //         //         // if($invoice->id == 13036)
    //         //         // {
    //         //         //     dd($creditAmount,$debitAmount,$variation);
    //         //         // }
    //         //         $transaction = $account->debit_transaction()->create([
    //         //             'amount' => $variation,
    //         //             'creator_id' => $invoice->creator_id,
    //         //             'invoice_id' => $invoice->id,
    //         //             'organization_id' => $invoice->organization_id,
    //         //             'description' => 'to_item',
    //         //         ]);

    //         //         // if($invoice->id == 13036)
    //         //         // {
    //         //         //     dd($transaction->toArray());
    //         //         // }
    //         //     } elseif ($creditAmount < $debitAmount) {
    //         //         $variation = $debitAmount - $creditAmount;
    //         //         $account->debit_transaction()->create([
    //         //             'amount' => $variation,
    //         //             'creator_id' => $invoice->creator_id,
    //         //             'invoice_id' => $invoice->id,
    //         //             'organization_id' => $invoice->organization_id,
    //         //             'description' => 'to_gateway',
    //         //         ]);


    //         //     }
    //         // }



    //         //     $items = [];
    //         //     foreach ($invoice->items as $item) {
    //         //         if ($item->item != null) {

    //         //             $parentInvoiceItem = $parentInvoice->items()->where('item_id', $item->item_id)->first();
    //         //             $base['id'] = $parentInvoiceItem->id;
    //         //             $base['price'] = $item->price;
    //         //             $base['returned_qty'] = $item->qty;
    //         //             $base['discount'] = $item->discount;


    //         //             if ($item->is_kit) {
    //         //                 $childItems = [];
    //         //                 foreach ($invoice->items()->where([
    //         //                     ['belong_to_kit', true],
    //         //                     ['parent_kit_id', $item->id],
    //         //                 ])->get() as $kitItem) {
    //         //                     $parentKitItemInvoiceItem = $parentInvoice->items()->where('item_id', $kitItem->item_id)->first();
    //         //                     $base2['id'] = $parentKitItemInvoiceItem->id;

    //         //                     if ($kitItem->item->is_need_serial) {
    //         //                         $base2['serials'] = $kitItem->item->serials()->where([
    //         //                             ['r_sale_invoice_id', $invoice->id],
    //         //                             ['current_status', 'r_sale']
    //         //                         ])->pluck('serial')->toArray();
    //         //                     }
    //         //                     $childItems[] = $base2;
    //         //                     $this->updateReturnSalesItem($parentKitItemInvoiceItem, $invoice);
    //         //                 }
    //         //                 $base['items'] = $childItems;
    //         //             } else {

    //         //                 if ($item->item->is_need_serial) {
    //         //                     $base['serials'] = $item->item->serials()->where(
    //         //                         [
    //         //                             ['r_sale_invoice_id', $invoice->id],
    //         //                             ['current_status', 'r_sale']
    //         //                         ]
    //         //                     )->pluck('serial')->toArray();
    //         //                 }
    //         //                 $this->updateReturnSalesItem($parentInvoiceItem, $invoice);

    //         //                 // dd($item->r_qty);
    //         //             }


    //         //             $items[] = $base;
    //         //         }
    //         //     }



    //         //     $payments = [];
    //         //     foreach ($invoice->payments as $payment) {
    //         //         $paymentable = $payment->paymentable;
    //         //         $paymentable->amount = $payment->amount;
    //         //         $payments[] = $paymentable->toArray();
    //         //     }


    //         //     if ($invoice->user()->is_system_user && $payments === []) {
    //         //         $payment = Account::where([
    //         //             ['is_system_account', true],
    //         //             ['slug', 'temp_reseller_account'],
    //         //         ])->first();
    //         //         $payment->amount = $invoice->net;

    //         //         $payments[] = $payment->toArray();
    //         //     }



    //         //     $invoice->items()->forceDelete();
    //         //     TransactionsContainer::where('invoice_id', $invoice->id)->forceDelete();
    //         //     Transaction::where('invoice_id', $invoice->id)->forceDelete();
    //         //     $invoice->payments()->forceDelete();
    //         //     $transactionContaniner = $this->createTransactionContainer($invoice);
    //         //     dispatch(new CreateReturnSalesItemsJob($transactionContaniner, $invoice, $items, $parentInvoice));
    //         //     dispatch(new UpdateInvoiceTotalsJob($invoice));
    //         //     dispatch(new CreateReturnSalesEntityTransactionsJob($transactionContaniner, $invoice, $payments));
    //         //     dispatch(new EnsureReturnSalesDataAreCorrectJob($invoice));
    //         // dd($transactionContaniner);
    //     }
    // }










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



    // private function updateSalesItem(InvoiceItems $item, Invoice $invoice)
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

    //     $current_qty = $currentQty + $changedQty;
    //     if (!$item->is_kit) {
    //         $item->item->update([
    //             'available_qty' => $current_qty,
    //         ]);
    //         if ($item->item->is_need_serial) {
    //             $item->item->serials()->where([
    //                 ['sale_invoice_id', $invoice->id],
    //                 ['current_status', 'saled']
    //             ])->update([
    //                 'current_status' => "available",
    //                 'sale_invoice_id' => 0
    //             ]);
    //         }
    //     }

    //     $this->changeVtsTo5($item->item);
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

    //     $this->changeVtsTo5($item->item);
    // }


    // private function changeVtsTo5(Item $item)
    // {

    //     $item->update([
    //         'vts' => 5
    //     ]);

    //     // dd($item->fresh()->toArray());

    //     $this->itemsUpdated[] = $item->id;
    // }
}
