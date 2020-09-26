<?php

namespace Tests\Feature\Sales;

use App\Models\Account;
use App\Models\Item;
use App\Models\ItemSerials;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateKitSaleTest extends TestCase
{
    use WithFaker;


    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_sales_invoice_for_kit_items()
    {

        $dbItems = Item::where([
                ['is_need_serial', false],
                ['is_service', false],
                ['is_expense', false],
                ['is_kit', true]
            ]
        )->take(1)->get();

        $frontEndItems = [];
        foreach ($dbItems as $item) {
            $requestItem = [];
            $requestItem['id'] = $item->id;
            $requestItem['qty'] = 1;
            $children = [];
            foreach ($item->items as $kitItem) {

                $requestKitItem = $kitItem->item->toArray();
                if ($kitItem->item->is_need_serial) {
                    $requestKitItem['serials'] = ItemSerials::where([
                        ['item_id', $kitItem->item->id],
                    ])->whereIn('status', ['in_stock', 'return_sale'])->inRandomOrder()->take($kitItem->qty * $requestItem['qty'])->pluck('serial')->toArray();
                }

                $requestKitItem['qty'] = $kitItem->qty * $item->qty;

                $children[] = $requestKitItem;
            }
            $requestItem['items'] = $children;

            $frontEndItems[] = $requestItem;
        }

//        dd($frontEndItems);

        $manager = factory(Manager::class)->create();
        $client = factory(User::class)->create([
            'is_client' => true
        ]);

        $response = $this->actingAs($manager)->postJson('/api/sales', [
            'items' => $frontEndItems,
            'client_id' => $client->id,
            'salesman_id' => $manager->id
        ]);

        $response
            ->dump()
            ->assertCreated();
    }
}
