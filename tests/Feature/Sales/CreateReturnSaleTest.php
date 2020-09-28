<?php

namespace Tests\Feature\Sales;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Item;
use App\Models\Manager;
use Tests\TestCase;

class CreateReturnSaleTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_return_sale()
    {

//        $invoice = Invoice::where('invoice_type', 'sale')->inRandomOrder()->first();
        $invoice = Invoice::find(18163);
        $items = [];
        foreach ($invoice->items as $item) {
            if (!$item->item->is_kit) {
                $requestItem = [];
                $requestItem['returned_qty'] = $item->qty;
                $requestItem['id'] = $item->id;

                if ($item->item->is_need_serial) {
                    $requestItem['serials'] = $item->item->serials()->where([
                        ['sale_id', $item->invoice_id],
                        ['status', 'sold'],
                    ])->pluck('serial')->toArray();
                    $this->assertEquals(count($requestItem['serials']), $requestItem['returned_qty']);
                }

                $qtyWidget = $requestItem['returned_qty'] /  $item->qty;
                $requestItem['testing_available_qty'] = $item->item->available_qty;
                $requestItem['test_item_returned_qty'] = $item->returned_qty;
                $requestItem['testing_total_profits_amount'] = (float)$item->item->total_profits_amount;
                $requestItem['testing_cost'] = $item->item->cost;
                $requestItem['testing_total_cost_amount'] = (int)$requestItem['returned_qty'] * (float)$item->item->cost;
                $requestItem['testing_subtotal'] = (float)(((float)$item->price * (float)$requestItem['returned_qty']) - (float)$item->discount * $qtyWidget);
                $requestItem['testing_total_credit_amount'] = (float)$item->item->total_credit_amount;
                $requestItem['testing_total_debit_amount'] = (float)$item->item->total_debit_amount;


                $items[] = $requestItem;
            }

        }

        $method = Account::where('slug','gateway')->first();
        $method->amount = $invoice->net;
        $manager = factory(Manager::class)->create();
        $this->assertInstanceOf(Invoice::class, $invoice);
        $response = $this->actingAs($manager)->patchJson('/api/sales/' . $invoice->id, [
            'items' => $items,
            'methods' => [$method->toArray()]
        ]);
        $response
             ->dump()
            ->assertCreated();

        foreach ($items as $item) {
            $dbInvoiceItem = InvoiceItems::find($item['id']);

            $dbItem = $dbInvoiceItem->item;

            // $this->assertEquals(roundMoney((float) $item['testing_total_debit_amount'] + (float) $item['testing_subtotal']), roundMoney($dbItem->total_debit_amount));
            $this->assertEquals(roundMoney($item['testing_total_credit_amount']), roundMoney($dbItem->total_credit_amount));
            $this->assertEquals((int)$item['testing_available_qty'] + (int)$item['returned_qty'], (int)$dbItem->available_qty);
            $this->assertEquals((int)$item['test_item_returned_qty'] + $item['returned_qty'], (int)$dbInvoiceItem->returned_qty);
            $this->assertEquals($item['testing_cost'], $dbItem->cost);
            $this->assertEquals(roundMoney($dbItem->total_profits_amount), roundMoney((float)$item['testing_total_profits_amount'] - (float)($item['testing_subtotal'] - (float)$item['testing_total_cost_amount'])));
        }

    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function create_return_sale_for_kits()
    {

        $kit = Item::where([
            ['is_kit', true],
        ])->withCount('pipeline')->having('pipeline_count', '>', 0)->inRandomOrder()->first();
        $this->assertNotNull($kit);

        $account = Account::first();
        $account->amount = 0;
//        dd($kit);
        $pipeline = $kit->pipeline()->where('invoice_type', 'sale')->inRandomOrder()->first();

//        dd($pipeline);
        $this->assertNotNull($pipeline);
        $invoice = $pipeline->invoice;
        $this->assertNotNull($invoice);
        $items = [];
        $dbItems = $invoice->items()->where('belong_to_kit', false)->get();
        foreach ($dbItems as $item) {

            $requestItem = [];
            $requestItem['returned_qty'] = 1;
            $requestItem['id'] = $item->id;


            if ($item->item->is_need_serial) {
                $requestItem['serials'] = $item->item->serials()->where([
                    ['sale_id', $item->invoice_id],
                    ['status', 'sold'],
                ])->take($requestItem['returned_qty'])->pluck('serial')->toArray();
                $this->assertEquals(count($requestItem['serials']), $requestItem['returned_qty']);
            }


            if ($item->item->is_kit) {
                $kitItems = $invoice->items()->where([
                    ['parent_kit_id', $item->id],
                    ['belong_to_kit', true],
                ])->get();

                $children = [];
                foreach ($kitItems as $kitItem) {
                    $qtyPerUnit = $kitItem->qty / $item->qty;
                    $child['id'] = $kitItem['id'];
                    $child['returned_qty'] = $qtyPerUnit * $requestItem['returned_qty'];
                    if ($kitItem->item->is_need_serial) {
                        $child['serials'] = $kitItem->item->serials()->where([
                            ['sale_id', $kitItem->invoice_id],
                            ['status', 'sold'],
                        ])->take($child['returned_qty'])->pluck('serial')->toArray();
                        $this->assertEquals(count($child['serials']), $child['returned_qty']);
                    }
                    $children[] = $child;
                }

                $requestItem['items'] = $children;
            }

            $items[] = $requestItem;
        }

//        dd($items);

        $manager = factory(Manager::class)->create();
        $this->assertInstanceOf(Invoice::class, $invoice);
        $response = $this->actingAs($manager)->patchJson('/api/sales/' . $invoice->id, [
            'items' => $items,
            'methods' => [$account->toArray()]
        ]);
        $response
            ->dump()
            ->assertCreated();
    }
}
