<?php

namespace Tests\Feature\Sales;

use App\Models\Item;
use App\Models\ItemSerials;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateSaleTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_sales_invoice_for_items_with_just_quantity()
    {

        $dbItems = Item::where([
//                ['is_need_serial', false],
                ['is_service', false],
                ['is_expense', false],
                ['is_kit', false]
            ]
        )->inRandomOrder()->take(10)->get();
        $items = [];
        foreach ($dbItems as $item) {
            $requestItem = [];
            $requestItem['id'] = $item->id;
            $requestItem['is_need_serial'] = $item->is_need_serial;
            $requestItem['price'] = $item->price;
            $requestItem['qty'] = $this->faker->numberBetween(1, abs($item->available_qty));
            $requestItem['discount'] = $this->faker->numberBetween(1, 5);
            if ($item->is_need_serial) {
                $requestItem['serials'] = ItemSerials::where([
                    ['item_id', $item->id],
                ])->whereIn('status', ['in_stock', 'return_sale'])->inRandomOrder()->take($requestItem['qty'])->pluck('serial')->toArray();
//                dd($requestItem['serials']);
            }

            $requestItem['testing_available_qty'] = $item->available_qty;
            $requestItem['testing_total_profits_amount'] = (float)$item->total_profits_amount;
            $requestItem['testing_cost'] = $item->cost;
            $requestItem['testing_total_cost_amount'] = (int)$requestItem['qty'] * (float)$item->cost;
            $requestItem['testing_subtotal'] = (float)(((float)$requestItem['price'] * (float)$requestItem['qty']) - (float)$requestItem['discount']);
            $requestItem['testing_total_credit_amount'] = (float)$item->total_credit_amount;
            $requestItem['testing_total_debit_amount'] = (float)$item->total_debit_amount;


            $items[] = $requestItem;
        }


        $manager = factory(Manager::class)->create();
        $client = factory(User::class)->create([
            'is_client' => true
        ]);

        $response = $this->actingAs($manager)->postJson('/api/sales', [
            'items' => $items,
            'client_id' => $client->id,
            'salesman_id' => $manager->id,
        ]);

        $response
            ->dump()
            ->assertCreated();


        foreach ($items as $item) {
            $dbItem = Item::find($item['id']);
            $this->assertEquals(roundMoney((float)$item['testing_total_credit_amount'] + (float)$item['testing_subtotal']), roundMoney($dbItem->total_credit_amount));
            $this->assertEquals(roundMoney($item['testing_total_debit_amount']), roundMoney($dbItem->total_debit_amount));
            $this->assertEquals((int)$item['testing_available_qty'] - (int)$item['qty'], (int)$dbItem->fresh()->available_qty);
            $this->assertEquals($item['testing_cost'], $dbItem->cost);
            $this->assertEquals(roundMoney($dbItem->total_profits_amount),
                roundMoney((float)$item['testing_total_profits_amount'] + (float)($item['testing_subtotal'] - (float)$item['testing_total_cost_amount'])));
        }


    }

}
