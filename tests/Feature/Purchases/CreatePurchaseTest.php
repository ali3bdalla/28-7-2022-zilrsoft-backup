<?php

namespace Tests\Feature\Purchases;

use App\Models\Item;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreatePurchaseTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_purchase_for_items_without_serial_and_expense_or_service_or_kit_without_discount()
    {

        $dbItems = Item::where([
            ['is_need_serial', false],
            ['is_service', false],
            ['is_expense', false],
            ['is_kit', false],
        ])->get();

        $vendor = factory(User::class)->create([
            'is_vendor' => true,
        ]);
        $items = [];
        foreach ($dbItems as $item) {
            $requestItem = [];
            $requestItem['id'] = $item->id;
            $requestItem['purchase_price'] = $this->faker->numberBetween(1, ($item->price));
            $requestItem['qty'] = $this->faker->numberBetween(1, 100);
            $requestItem['discount'] = 0;
            $requestItem['price'] = $item->price;
            $requestItem['testing_available_qty'] = (int) $item->available_qty;
            $requestItem['testing_item_cost'] = $item->cost;
            $requestItem['testing_item_total_stock_amount'] = ($item->available_qty * $item->cost);
            $items[] = $requestItem;
        }
        $manager = factory(Manager::class)->create();
        $response = $this->actingAs($manager)->postJson('/api/purchases', [
            'items' => $items,
            'vendor_id' => $vendor->id,
            'receiver_id' => Manager::inRandomOrder()->first()->id,
            'vendor_invoice_id' => $this->faker->uuid,
        ]);
        $response
//            ->dump()
            ->assertOk();
        foreach ($items as $item) {
            $dbItem = Item::find($item['id']);
            $this->assertEquals((int) $item['testing_available_qty'] + (int) $item['qty'], (int) $dbItem->fresh()->available_qty);
            $this->assertEquals(roundMoney((float) (((float) $item['testing_item_total_stock_amount'] + ((float) $item['purchase_price'] * (int) $item['qty'])) / $dbItem->available_qty)), roundMoney($dbItem->cost));
        }
    }

    
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_purchase_for_items_without_expense_or_service_or_kit_with_discount_and_serial()
    {

        $dbItems = Item::where([
            ['is_need_serial', true],
            ['is_service', false],
            ['is_expense', false],
            ['is_kit', false],
        ])->get();

        $vendor = factory(User::class)->create([
            'is_vendor' => true,
        ]);
        $items = [];
        foreach ($dbItems as $item) {
            $requestItem = [];
            $requestItem['id'] = $item->id;
            $requestItem['purchase_price'] = $this->faker->numberBetween(1, ($item->price));
            $requestItem['qty'] = $this->faker->numberBetween(1, 100);
            $requestItem['discount'] = 0;
            $requestItem['price'] = $item->price;

            $requestItem['testing_available_qty'] = (int) $item->available_qty;
            $requestItem['testing_item_cost'] = $item->cost;
            $requestItem['testing_item_total_stock_amount'] = ($item->available_qty * $item->cost);
            $requestItem['serials'] = $this->generateSerials($requestItem['qty']);
            $items[] = $requestItem;
        }
        $manager = factory(Manager::class)->create();
        $response = $this->actingAs($manager)->postJson('/api/purchases', [
            'items' => $items,
            'vendor_id' => $vendor->id,
            'receiver_id' => Manager::inRandomOrder()->first()->id,
            'vendor_invoice_id' => $this->faker->uuid,
        ]);
        $response
//            ->dump()
            ->assertOk();
        foreach ($items as $item) {
            $dbItem = Item::find($item['id']);
            $this->assertEquals((int) $item['testing_available_qty'] + (int) $item['qty'], (int) $dbItem->fresh()->available_qty);
            $this->assertEquals(roundMoney((float) (((float) $item['testing_item_total_stock_amount'] + (((float) $item['purchase_price'] * (int) $item['qty']) - (float) $requestItem['discount'])) / $dbItem->available_qty)), roundMoney($dbItem->cost));

            foreach ($item['serials'] as $serial) {
                $result = $dbItem->serials()->where([
                    ['serial', $serial],
                    ['status', 'in_stock'],

                ])->get();
                $this->assertNotEmpty($result);
            }
        }
    }

    public function generateSerials($count)
    {
        $serials = [];
        for ($i = 0; $i < $count; $i++) {
            $serials[] = uniqid(uniqid());
        }

        return $serials;
    }
}
