<?php

namespace Tests\Feature\App;

use App\Jobs\Time\Invoice\UpdateInvoiceCreatedAtJob;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\ItemSerials;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CreateInvoicesTest extends TestCase
{


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

//        ->where([
//        ['id', 21648]
//    ])
        $invoices = $this->dbConnection->table('invoices')->get();

        $createdInvoiceId = 0;
        foreach ($invoices as $invoice) {
            if ($invoice->invoice_type == 'beginning_inventory') {
                $createdInvoiceId = $this->createBeginningInventory($invoice);
            } elseif ($invoice->invoice_type == 'purchase') {
                $createdInvoiceId = $this->createPurchase($invoice);
            } //
            elseif ($invoice->invoice_type == 'r_purchase') {
                $this->createReturnPurchase($invoice);

            }
// elseif ($invoice->invoice_type == 'sale') {
//                $this->createSale($invoice);
//
//            } elseif ($invoice->invoice_type == 'r_sale') {
//                $this->createReturnSale($invoice);
//
//            }


            if ($createdInvoiceId > 0) {
                dispatch(new UpdateInvoiceCreatedAtJob($createdInvoiceId, $invoice->created_at));

            }
        }

    }

    public function createBeginningInventory($invoice)
    {

        $dbItems = $this->dbConnection->table('invoice_items')->where([
            ['invoice_id', $invoice->id]
        ])->take(100)->get();

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
            $response = $this->actingAs($manager)->postJson('/api/purchases/beginning', [
                'items' => $items,
            ]);
            $response
                ->dump()
                ->assertOk();

            return json_decode($response->content(), true)['id'];

        }


        return 0;


    }

    public function createPurchase($invoice)
    {
        $purchase = $this->dbConnection->table('purchase_invoices')->where('invoice_id', $invoice->id)->first();
        $dbItems = $this->dbConnection->table('invoice_items')->where([
            ['invoice_id', $invoice->id]
        ])->take(100)->get();

        if ($purchase != null && $dbItems != null) {
            $items = [];
            foreach ($dbItems as $item) {

                $dbItem = $this->dbConnection->table('items')->find($item->item_id);
                $newDBItem = Item::find($item->item_id);
                if ($dbItem != null && $newDBItem != null) {
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


                return json_decode($response->content(), true)['id'];
            }


        }


        return 0;
    }

    public function createReturnPurchase($invoice)
    {
        $purchase = $this->dbConnection->table('purchase_invoices')->where('invoice_id', $invoice->id)->first();
        $dbItems = $this->dbConnection->table('invoice_items')->where([
            ['invoice_id', $invoice->id]
        ])->take(100)->get();

        if ($purchase != null && $dbItems != null) {
            $items = [];
            foreach ($dbItems as $item) {

                $dbItem = $this->dbConnection->table('items')->find($item->item_id);
                $newDBItem = Item::find($item->item_id);
                if ($dbItem != null && $newDBItem != null) {
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


                return json_decode($response->content(), true)['id'];
            }


        }


        return 0;
    }

    public function createSale($invoice)
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function createReturnSale($invoice)
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->dbConnection = DB::connection('data_source');

    }
}
