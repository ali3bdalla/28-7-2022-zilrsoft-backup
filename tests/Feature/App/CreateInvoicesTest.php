<?php

namespace Tests\Feature\App;

use App\Jobs\Time\Invoice\UpdateInvoiceCreatedAtJob;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\ItemSerials;
use App\Models\KitItems;
use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CreateInvoicesTest extends TestCase
{

    private $salesMassInvoices = [];

    private $updatedItems = [];
    /**
     * @var ConnectionInterface
     */
    private $dbConnection;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_invoices()
    {


        // sales quantity issue => [1442,1599,3252,3482,3908,5111,4418,4419,4420,4447,4475,4508,4511,4549,4552,4563,4571,4575,4589,4591,4597,4601,4625,4634]
        //[4637,4642,4647,4652,4664,4708,4714,4724,4728,4729,4749,4764,4778,4781,4797,4817,4845,4894,4903,4905,4913,4924,4942,4964,4966,4993,5010,5014,5017
        // 5031,9429,9969,9970
//        ->where('id', '=', 241)
        $invoices = $this->dbConnection->table('invoices')->where('id', '>', 9970)->get();
        //

        foreach ($invoices as $invoice) {
            echo "\nstarting.......................\n";
            echo "source id " . $invoice->id . "\n";
            echo 'source type ' . $invoice->invoice_type . "\n";
            $createdInvoiceId = 0;

            if ($invoice->invoice_type == 'beginning_inventory') {
                $createdInvoiceId = $this->createBeginningInventory($invoice);
            } elseif ($invoice->invoice_type == 'purchase') {
                $createdInvoiceId = $this->createPurchase($invoice);
            } elseif ($invoice->invoice_type == 'r_purchase') {
                $createdInvoiceId = $this->createReturnPurchase($invoice);
            } elseif ($invoice->invoice_type == 'sale') {
                $createdInvoiceId = $this->createSale($invoice);
            } elseif ($invoice->invoice_type == 'r_sale') {
                $createdInvoiceId = $this->createReturnSale($invoice);
            }

//            dd($createdInvoiceId)

            if ($createdInvoiceId > 0) {
                dispatch(new UpdateInvoiceCreatedAtJob($createdInvoiceId, $invoice->created_at));

                $this->dbConnection->table('invoices')->where('id', $invoice->id)->update([
                    'new_db_id' => $createdInvoiceId
                ]);


            }


            echo 'created invoice id ' . $createdInvoiceId . "\n";
            echo "ending ............... \n";


            $totalDebitAmount = Account::sum('total_debit_amount');
            $totalCreditAmount = Account::sum('total_credit_amount');
            $variation = abs($totalDebitAmount - $totalCreditAmount);
            $this->assertLessThanOrEqual(1, $variation);
        }


        print_r($this->salesMassInvoices);
    }

    public function createBeginningInventory($invoice)
    {

        $dbItems = $this->dbConnection->table('invoice_items')->where([
            ['invoice_id', $invoice->id]
        ])->get();

        $items = [];
        foreach ($dbItems as $item) {
            $requestItem = [];
            $requestItem['id'] = $item->item_id;
            $requestItem['purchase_price'] = $item->price;
            $requestItem['qty'] = $item->qty;
            $requestItem['discount'] = 0;
            $dbItem = $this->dbConnection->table('items')->find($item->item_id);
            $newDBItem = Item::find($item->item_id);

            if ($dbItem != null && $newDBItem != null) {
                $this->updateItemDetails($newDBItem, $invoice);
                if ($dbItem->is_need_serial) {
                    $requestItem['serials'] = $this->dbConnection->table('item_serials')->where([
                        ['purchase_invoice_id', $invoice->id],
                        ['item_id', $dbItem->id],
                    ])->pluck('serial')->toArray();
                    $requestItem['qty'] = count($requestItem['serials']);
                }


                if ($requestItem['qty'] <= 0) {
                    continue;
                }

                $requestItem['testing_available_qty'] = (int)$newDBItem->available_qty;
                $requestItem['testing_item_cost'] = $newDBItem->cost;
                $requestItem['testing_item_total_stock_amount'] = ($newDBItem->available_qty * $newDBItem->cost);
                $requestItem['testing_subtotal'] = ((float)$requestItem['purchase_price'] * (float)$requestItem['qty']) - (float)$requestItem['discount'];
                $requestItem['testing_total_credit_amount'] = $newDBItem->total_credit_amount;
                $requestItem['testing_total_debit_amount'] = $newDBItem->total_debit_amount;


                $items[] = $requestItem;
            }

        }


        $manager = Manager::find(1);

        if ($items != null) {
            $response = $this->actingAs($manager)->postJson('/api/inventory/beginning', [
                'items' => $items,
            ]);
            $response
                ->dump()
                ->assertOk();
            $this->restUpdatedItemsDetails();

            return json_decode($response->content(), true)['id'];

        }

        $this->restUpdatedItemsDetails();

        return 0;


    }

    public function updateItemDetails(Item $dbItem, $invoice)

    {

        if (Carbon::parse($invoice->created_at)->lte(Carbon::parse('30-07-2020'))) {
            $dbItem->update([
                'vts' => 5,
                'vtp' => 5,
            ]);

            $this->updatedItems[] = $dbItem;
        }


    }

    public function restUpdatedItemsDetails()
    {
        foreach ($this->updatedItems as $item) {
            $item->update([
                'vts' => 15,
                'vtp' => 15,
            ]);
        }

        $this->updatedItems = [];
    }

    public function createPurchase($invoice)
    {
        $purchase = $this->dbConnection->table('purchase_invoices')->where('invoice_id', $invoice->id)->first();
        $dbItems = $this->dbConnection->table('invoice_items')->where([
            ['invoice_id', $invoice->id]
        ])->get();


        if ($purchase != null && $dbItems != null) {
            $items = [];
            foreach ($dbItems as $item) {

                $dbItem = $this->dbConnection->table('items')->find($item->item_id);
                $newDBItem = Item::find($item->item_id);
                if ($dbItem != null && $newDBItem != null) {
                    $this->updateItemDetails($newDBItem, $invoice);

                    $requestItem = [];
                    $requestItem['id'] = $item->item_id;
                    $requestItem['purchase_price'] = $item->price;
                    $requestItem['price'] = $dbItem->price;
                    $requestItem['qty'] = $item->qty;
                    $requestItem['discount'] = $item->discount;
                    if ($dbItem->is_need_serial) {
                        $serials = $this->dbConnection->table('item_serials')->where([
                            ['purchase_invoice_id', $invoice->id],
                            ['item_id', $dbItem->id],
                        ])->pluck('serial')->toArray();
//                        $requestItem['serials']

                        $requestSerials = [];
                        foreach ($serials as $serial) {
                            $dbSerial = ItemSerials::where([
                                ['serial', $serial],
                                ['item_id', $dbItem->id]
                            ])->whereIn('status', ['in_stock', 'return_sale'])->first();
                            if ($dbSerial == null) {
                                $requestSerials[] = $serial;

                            }
                        }

                        $requestItem['serials'] = $requestSerials;
                        $requestItem['qty'] = count($requestItem['serials']);
                    }


                    if ($requestItem['qty'] <= 0) {
                        continue;
                    }

                    $requestItem['testing_available_qty'] = (int)$newDBItem->available_qty;
                    $requestItem['testing_item_cost'] = $newDBItem->cost;
                    $requestItem['testing_item_total_stock_amount'] = ($newDBItem->available_qty * $newDBItem->cost);
                    $requestItem['testing_subtotal'] = ((float)$requestItem['purchase_price'] * (float)$requestItem['qty']) - (float)$requestItem['discount'];
                    $requestItem['testing_total_credit_amount'] = $newDBItem->total_credit_amount;
                    $requestItem['testing_total_debit_amount'] = $newDBItem->total_debit_amount;


                    $items[] = $requestItem;
                }

            }


            $manager = Manager::find(1);

            if ($items != null) {
                $response = $this->actingAs($manager)->postJson('/api/purchases', [
                    'items' => $items,
                    'vendor_id' => $purchase->vendor_id,
                    'receiver_id' => $purchase->receiver_id,
                    'vendor_invoice_id' => $purchase->vendor_inc_number
                ]);
                $response
                    ->dump()
                    ->assertOk();

                $this->restUpdatedItemsDetails();

                return json_decode($response->content(), true)['id'];
            }


        }

        $this->restUpdatedItemsDetails();

        return 0;
    }

    public function createReturnPurchase($invoice)
    {
        $parentInvoice = $this->dbConnection->table('invoices')->where('id', $invoice->parent_invoice_id)->first();

        if ($parentInvoice)
            $newInvoice = Invoice::find($parentInvoice->new_db_id);
        else
            $newInvoice = null;


        if ($newInvoice) {
            $purchase = $this->dbConnection->table('purchase_invoices')->where('invoice_id', $invoice->id)->first();
            $dbItems = $this->dbConnection->table('invoice_items')->where([
                ['invoice_id', $invoice->id]
            ])->get();

            if ($purchase != null && $dbItems != null) {
                $items = [];
                foreach ($dbItems as $item) {

                    $dbItem = $this->dbConnection->table('items')->find($item->item_id);
                    $newDBItem = Item::find($item->item_id);
                    if ($dbItem != null && $newDBItem != null) {
                        $this->updateItemDetails($newDBItem, $invoice);

                        $requestItem = [];
                        $requestItem['id'] = $newInvoice->items()->where('item_id', $item->item_id)->first()->id;

                        $requestItem['returned_qty'] = $item->qty;
                        if ($dbItem->is_need_serial) {
                            $serials = $this->dbConnection->table('item_serials')->where([
                                ['r_purchase_invoice_id', $invoice->id],
                                ['item_id', $dbItem->id],
                            ])->pluck('serial')->toArray();
//                        $requestItem['serials']


                            $requestSerials = [];
                            foreach ($serials as $serial) {
                                $dbSerial = ItemSerials::where([
                                    ['serial', $serial],
                                    ['item_id', $dbItem->id]
                                ])->whereIn('status', ['return_purchase'])->first();
                                if ($dbSerial == null) {
                                    $requestSerials[] = $serial;

                                }
                            }

                            $requestItem['serials'] = $requestSerials;
                            $requestItem['returned_qty'] = count($requestItem['serials']);
                        }


                        if ($requestItem['returned_qty'] <= 0) {
                            continue;
                        }


                        $items[] = $requestItem;
                    }

                }


                $manager = Manager::find(1);

                if ($items != null) {
                    $response = $this->actingAs($manager)->patchJson("/api/purchases/{$newInvoice->id}", [
                        'items' => $items,
                    ]);
                    $response
                        ->dump()
                        ->assertOk();

                    $this->restUpdatedItemsDetails();

                    return json_decode($response->content(), true)['id'];
                }


            }

            $this->restUpdatedItemsDetails();
        }


        return 0;
    }

    public function createSale($invoice)
    {
        $sale = $this->dbConnection->table('sale_invoices')->where('invoice_id', $invoice->id)->first();
        $dbItems = $this->dbConnection->table('invoice_items')->where([
            ['invoice_id', $invoice->id],
            ['belong_to_kit', false]
        ])->get();

        $tempResellerAccount = Account::where('slug', 'temp_reseller_account')->first()->toArray();

        $paidAmount = $this->dbConnection->table('invoice_payments')->where('invoice_id', $invoice->id)->sum('amount');

        $tempResellerAccount['amount'] = $paidAmount;


        if ($sale != null && $dbItems != null) {
            $items = [];
            foreach ($dbItems as $item) {
                $dbItem = $this->dbConnection->table('items')->find($item->item_id);
                $newDBItem = Item::find($item->item_id);
                if ($dbItem != null && $newDBItem != null) {

                    $this->updateItemDetails($newDBItem, $invoice);

                    $requestItem = [];
                    $requestItem['id'] = $item->item_id;
                    $requestItem['price'] = $item->price;
                    $requestItem['qty'] = $item->qty;
                    $requestItem['discount'] = $item->discount;


                    if ($dbItem->is_need_serial) {
                        $serials = $this->dbConnection->table('item_serials')->where([
                            ['sale_invoice_id', $invoice->id],
                            ['item_id', $dbItem->id],
                        ])->pluck('serial')->toArray();
                        $requestSerials = [];
                        foreach ($serials as $serial) {
                            $dbSerial = ItemSerials::where([
                                ['serial', $serial],
                                ['item_id', $dbItem->id]
                            ])->whereIn('status', ['sold'])->first();
                            if ($dbSerial == null) {
                                $requestSerials[] = $serial;

                            }
                        }

                        $requestItem['serials'] = $requestSerials;
                        $requestItem['qty'] = count($requestItem['serials']);
                    }


                    if ($dbItem->is_kit) {
                        $dbKitItems = $this->dbConnection->table('invoice_items')->where([
                            ['invoice_id', $invoice->id],
                            ['belong_to_kit', true],
                            ['parent_kit_id', $item->id],
                        ])->get();
                        $requestItem['items'] = [];

                        foreach ($dbKitItems as $kitItem) {

                            $dbKitItem = $this->dbConnection->table('items')->find($kitItem->item_id);
                            $kitItem = KitItems::where([
                                ['item_id', $dbKitItem->id],
                                ['kit_id', $item->id],
                            ])->first();
                            $newDBInstance = Item::find($dbKitItem->id);

                            if ($kitItem != null && $newDBInstance != null) {

                                $this->updateItemDetails($newDBInstance, $invoice);


                                $requestKitItem['qty'] = $kitItem->qty;
                                $requestKitItem['id'] = $dbKitItem->id;
                                if ($dbKitItem->is_need_serial) {
                                    $requestKitItem['serials'] = $this->dbConnection->table('item_serials')->where([
                                        ['sale_invoice_id', $invoice->id],
                                        ['item_id', $dbKitItem->id],
                                    ])->pluck('serial')->toArray();
                                    $requestKitItem['qty'] = count($requestKitItem['serials']);
                                }
                                $requestItem['items'][] = $requestKitItem;
                            }

                        }
                        if ($requestItem['items'] == null) {
                            continue;
                        }
                    }


                    if ($requestItem['qty'] <= 0) {
                        continue;
                    }

                    $items[] = $requestItem;


                }

            }
            $manager = Manager::find(1);


            if ($items != null) {
                $response = $this->actingAs($manager)->postJson('/api/sales', [
                    'items' => $items,
                    'client_id' => $sale->client_id,
                    'salesman_id' => $sale->salesman_id,
                    'methods' => [$tempResellerAccount],
                    'without_creating_expenses_purchases' => true
                ]);
                $response
                    ->dump();

                if ($response->status() == 201) {
                    $response->assertCreated();
                    return json_decode($response->content(), true)['id'];


                } else {
                    $this->salesMassInvoices[] = $invoice->id;
                }
                $this->restUpdatedItemsDetails();

            }


        }

        $this->restUpdatedItemsDetails();

        return 0;
    }

    public function createReturnSale($invoice)
    {

        $parentInvoice = $this->dbConnection->table('invoices')->where('id', $invoice->parent_invoice_id)->first();

        if ($parentInvoice)
            $newInvoice = Invoice::find($parentInvoice->new_db_id);
        else
            $newInvoice = null;


        if ($newInvoice != null) {
            $sale = $this->dbConnection->table('sale_invoices')->where('invoice_id', $invoice->id)->first();
            $dbItems = $this->dbConnection->table('invoice_items')->where([
                ['invoice_id', $invoice->id],
                ['belong_to_kit', false]
            ])->get();

            $tempResellerAccount = Account::where('slug', 'temp_reseller_account')->first()->toArray();

            $paidAmount = $this->dbConnection->table('invoice_payments')->where('invoice_id', $invoice->id)->sum('amount');

            $tempResellerAccount['amount'] = $paidAmount;


            if ($sale != null && $dbItems != null) {
                $items = [];


                foreach ($dbItems as $key => $item) {

                    $dbItem = $this->dbConnection->table('items')->find($item->item_id);

                    $newDBItem = Item::find($item->item_id);

                    if ($newDBItem != null) {
                        $purchaseInvoiceItem = $newInvoice->items()->where('item_id', $newDBItem->id)->first();

                    } else {
                        $purchaseInvoiceItem = null;
                    }


                    if ($dbItem != null && $newDBItem != null && $purchaseInvoiceItem != null) {

                        $this->updateItemDetails($newDBItem, $invoice);

                        $requestItem = [];
                        $requestItem['id'] = $purchaseInvoiceItem->id;
                        $requestItem['returned_qty'] = $item->qty;


                        if ($dbItem->is_need_serial) {
                            $serials = $this->dbConnection->table('item_serials')->where([
                                ['sale_invoice_id', $invoice->id],
                                ['item_id', $dbItem->id],
                            ])->pluck('serial')->toArray();
                            $requestSerials = [];
                            foreach ($serials as $serial) {
                                $dbSerial = ItemSerials::where([
                                    ['serial', $serial],
                                    ['item_id', $dbItem->id]
                                ])->whereIn('status', ['return_sale'])->first();
                                if ($dbSerial == null) {
                                    $requestSerials[] = $serial;

                                }
                            }


                            $requestItem['serials'] = $requestSerials;
                            $requestItem['returned_qty'] = count($requestItem['serials']);
                        }


                        if ($dbItem->is_kit) {

                            $dbKitItems = $this->dbConnection->table('invoice_items')->where([
                                ['invoice_id', $invoice->id],
                                ['belong_to_kit', true],
                                ['parent_kit_id', $item->id],
                            ])->get();
                            $requestItem['items'] = [];

                            foreach ($dbKitItems as $kitItem) {

                                $dbKitItem = $this->dbConnection->table('items')->find($kitItem->item_id);
                                $kitItem = KitItems::where([
                                    ['item_id', $dbKitItem->id],
                                    ['kit_id', $item->id],
                                ])->first();
                                $newDBInstance = Item::find($dbKitItem->id);

                                $invoiceKitPurchaseItem = $newInvoice->items()->where([
                                    ['belong_to_kit', true],
                                    ['item_id', $newDBInstance->id]
                                ])->first();
                                if ($kitItem != null && $newDBInstance != null && $invoiceKitPurchaseItem != null) {

                                    $this->updateItemDetails($newDBInstance, $invoice);


                                    $requestKitItem['returned_qty'] = $kitItem->qty;
                                    $requestKitItem['id'] = $invoiceKitPurchaseItem->id;
                                    if ($dbKitItem->is_need_serial) {
                                        $requestKitItem['serials'] = $this->dbConnection->table('item_serials')->where([
                                            ['sale_invoice_id', $invoice->id],
                                            ['item_id', $dbKitItem->id],
                                        ])->pluck('serial')->toArray();
                                        $requestKitItem['returned_qty'] = count($requestKitItem['serials']);
                                    }
                                    $requestItem['items'][] = $requestKitItem;
                                }

                            }
                            if ($requestItem['items'] == null) {
                                continue;
                            }
                        }


                        if ($requestItem['returned_qty'] <= 0) {
                            continue;
                        }


                        $items[] = $requestItem;


                    }


                }


//                if($key > 0)
//                {
//                    dd($items);
//
//                }
                $manager = Manager::find(1);


                if ($items != null) {
                    $response = $this->actingAs($manager)->patchJson("/api/sales/{$newInvoice->id}", [
                        'items' => $items,
                        'methods' => [$tempResellerAccount]
                    ]);
//                    dd($items);
                    $response
                        ->dump()
                        ->assertCreated();

                    $this->restUpdatedItemsDetails();

                    return json_decode($response->content(), true)['id'];
                }


            }
        }


        $this->restUpdatedItemsDetails();

        return 0;

    }

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->dbConnection = DB::connection('data_source');

    }
}
