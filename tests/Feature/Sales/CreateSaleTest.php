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
    public function test_create_sale_non_serialed_item()
    {

        $dbItems = Item::inRandomOrder()->take(10)->get();

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

                ])->whereIn('current_status', ['purchase', 'available', 'return_sale'])->take($item['qty'])->pluck('serial')->toArray();
            }


            if($item->is_expense)
            {
                $requestItem['purchase_price'] = $item->price;

            }
            $items[] = $requestItem;
        }

        dd($items);
        $manager = factory(Manager::class)->create();

        $response = $this->actingAs($manager)->postJson('/sales', [
            'items' => $items,
            'client_id' => User::where('is_client', true)->inRandomOrder()->first()->id,
            'salesman_id' => Manager::inRandomOrder()->first()->id,
        ]);

        $response
        ->dump()
            ->assertStatus(200);
    }

}
