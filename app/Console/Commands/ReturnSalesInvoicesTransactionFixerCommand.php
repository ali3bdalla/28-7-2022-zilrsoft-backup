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

class ReturnSalesInvoicesTransactionFixerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:return_sales_invoices_transactions_fixer_command';

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
            $escapeInvoice = [14134,14032,14621,14664,15058,15381]; //13036,13037,13038,13039,13063,13074

            foreach(Invoice::find($escapeInvoice) as $invoice)
            {
                dispatch(new DeleteSaleInvoiceJob($invoice));
            }
            $invoices = Invoice::whereNotIn('id',   $escapeInvoice)->where([
                ['invoice_type', 'r_sale'],
            ])->get();
            
            // $invoices = Invoice::find([14134]);
            foreach ($invoices as $invoice) {
                $parentInvoice = Invoice::find($invoice->parent_invoice_id);
                $activeInvoice = $invoice->id;
                auth()->loginUsingId($invoice->creator_id);
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


                if ($creditAmount == 0 || $debitAmount == 0 || $parentInvoice == null) {
                    dispatch(new DeleteSaleInvoiceJob($invoice));
                    continue;
                }

                $def = (float)$creditAmount - (float)$debitAmount;
                if (abs($def) > 1) {
                    $items = $this->fetchItems($invoice, $parentInvoice);
                    if ($items == null) {
                        dispatch(new DeleteSaleInvoiceJob($invoice));
                        continue;
                    }
                    $payments = $this->fetchPayments($invoice);
                    $this->deleteMasDetails($invoice);
                    $this->createNewInvoice($invoice, $items, $payments);
                }
            }
            $this->resetVatSaleToDefault();
            // DB::rollBack();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getTrace(), $activeInvoice);
            throw $e;
        }
    }


    public function fetchItems(Invoice $invoice, Invoice $parentInvoice)
    {
        $items = [];
        $itemsAndKitsDB = $invoice->items()->where('belong_to_kit', false)->get();
        foreach ($itemsAndKitsDB as $invoiceItem) {
            $parentInvoiceItem = $parentInvoice->items()->where('item_id', $invoiceItem->item_id)->first();
            if ($invoiceItem == null || $invoiceItem->item == null || $parentInvoiceItem == null) {
                $items = null;
                break;
            }
            $newInvoiceItem = [];
            $newInvoiceItem['id'] = $parentInvoiceItem->id;
            $newInvoiceItem['price'] = $invoiceItem->price;
            $newInvoiceItem['returned_qty'] = $invoiceItem->qty;

            if ($invoiceItem->is_kit) {
                $kitItemsArray = [];
                foreach ($invoice->items()->where([
                    ['belong_to_kit', true],
                    ['parent_kit_id', $invoiceItem->id],
                ])->get() as $invoiceKitItem) {
                    $parentInvoiceKitItem = $parentInvoice->items()->where([
                        ['item_id', $invoiceItem->item_id],
                        ['belong_to_kit', true]
                    ])->first();
                    $newInvoiceKitItem['id'] = $parentInvoiceKitItem->id;
                    if ($invoiceKitItem->item->is_need_serial) {
                        $newInvoiceKitItem['serials'] = $invoiceKitItem->item->serials()->where([
                            ['r_sale_invoice_id', $invoice->id],
                            ['current_status', 'r_sale']
                        ])->pluck('serial')->toArray();
                    }
                    $kitItemsArray[] = $newInvoiceKitItem;
                    $this->changetItemQtyAndDetails($parentInvoiceKitItem, $invoice);
                }
                $newInvoiceItem['items'] = $kitItemsArray;
            } else {
                if ($invoiceItem->item->is_need_serial) {
                    $serials = $invoiceItem->item->serials()->where(
                        [
                            ['r_sale_invoice_id', $invoice->id],
                            ['current_status', 'r_sale']
                        ]
                    )->pluck('serial')->toArray();
                    // dd($serials);
                    $newInvoiceItem['serials'] = $serials;
                }

                $this->changetItemQtyAndDetails($parentInvoiceItem, $invoice);
            }
            $items[] = $newInvoiceItem;
        }

        return $items;
    }



    private function changetItemQtyAndDetails(InvoiceItems $parentInvoiceItem, Invoice $invoice)
    {

        // $current_qty = $item->item->available_qty - $item->r_qty;


        $currentQty = $parentInvoiceItem->item->available_qty - $parentInvoiceItem->r_qty;
        if ($currentQty <  0) {
            $currentQty = 0;
        }
        if (!$parentInvoiceItem->is_kit) {
            $parentInvoiceItem->update([
                'r_qty' => 0,
            ]);
            $parentInvoiceItem->item->update([
                'available_qty' => $currentQty,
            ]);
            if ($parentInvoiceItem->item->is_need_serial) {
                $parentInvoiceItem->item->serials()->where([
                    ['r_sale_invoice_id', $invoice->id],
                    ['current_status', 'r_sale']
                ])->update([
                    'current_status' => "saled",
                    'r_sale_invoice_id' => 0
                ]);
            }
        }

        if (Carbon::parse($invoice->created_at)->lte(Carbon::parse('30-07-2020'))) {
            $parentInvoiceItem->item()->update([
                'vts' => 5
            ]);

            $this->changedItemsVtsIds[] = $parentInvoiceItem->item_id;
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




    public function insureThereIsPaymentsForSystemUser(Invoice $invoice, $payments = [])
    {
        $totalPayments = collect($payments)->sum('amount');
        if ($invoice->user()->is_system_user && $payments === []) {
            $payment = Account::where([
                ['is_system_account', true],
                ['slug', 'temp_reseller_account'],
            ])->first();
            $payment->amount = $invoice->net;
            $totalPayments += $invoice->net;
            $payments[] = $payment->toArray();
        }


        if ($invoice->user()->is_system_user && $totalPayments < $invoice->net) {
           $payments[0]['amount'] = $invoice->net - $totalPayments;
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

        // dd($items,InvoiceItems::whereIn('id',collect($items)->pluck('id'))->with('item')->get()->toArray(),$payments,$invoice->toArray(),$invoice->user()->toArray());
        $entity = $this->createEntity($invoice);
        dispatch(new CreateReturnSalesItemsJob($entity, $invoice, $items));
        dispatch(new UpdateInvoiceTotalsJob($invoice));
        $payments = $this->insureThereIsPaymentsForSystemUser($invoice, $payments);
        dispatch(new CreateReturnSalesEntityTransactionsJob($entity, $invoice, $payments));
        dispatch(new EnsureReturnSalesDataAreCorrectJob($invoice, true));
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


    public function resetVatSaleToDefault()
    {
        Item::whereIn('id', $this->changedItemsVtsIds)->update([
            'vts' => 15
        ]);
    }





    // private function fixReturnSales()
    // {
    //     // $account = Account::find(24);
    //     //1090 , 13036, 13037, 13038, 13039, 13063, 13074, 14032, 13334, 14134, 14664, 15058
    //     
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
