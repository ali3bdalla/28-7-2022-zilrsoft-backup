<?php

namespace Tests\Feature\Purchases;

use App\Models\Item;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateDraftPurchaseTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_draft_purchase_for_items_without_serial_and_expense_or_service_or_kit_without_discount()
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
            $requestItem['purchase_price'] = $this->faker->numberBetween(0.001, ($item->price));
            $requestItem['qty'] = $this->faker->numberBetween(1, 100);
            $requestItem['discount'] = 0;
            $requestItem['price'] = $item->price;
            $requestItem['testing_available_qty'] = (int)$item->available_qty;
            $requestItem['testing_item_cost'] = $item->cost;
            $requestItem['testing_item_total_stock_amount'] = $item->total_cost_amount;
            $items[] = $requestItem;
        }
        $manager = factory(Manager::class)->create();
        $response = $this->actingAs($manager)->postJson('/api/purchases/draft', [
            'items' => $items,
            'vendor_id' => $vendor->id,
            'receiver_id' => Manager::inRandomOrder()->first()->id,
            'vendor_invoice_id' => $this->faker->uuid,
        ]);
        $response
            ->assertOk();
        foreach ($items as $item) {
            $dbItem = Item::find($item['id']);
            $this->assertEquals((int)$item['testing_available_qty'], (int)$dbItem->fresh()->available_qty);
            $this->assertEquals((int)$item['testing_item_cost'], (int)$dbItem->fresh()->cost);
            $this->assertEquals((int)$item['testing_item_total_stock_amount'], (int)$dbItem->fresh()->total_cost_amount);

        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_draft_purchase_for_items_without_expense_or_service_or_kit_with_discount_and_serial()
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
            $requestItem['purchase_price'] = $this->faker->numberBetween(0.001, ($item->price));
            $requestItem['qty'] = $this->faker->numberBetween(1, 100);
            $requestItem['discount'] = 0;
            $requestItem['price'] = $item->price;
            $requestItem['testing_available_qty'] = (int)$item->available_qty;
            $requestItem['testing_item_cost'] = $item->cost;
            $requestItem['testing_item_total_stock_amount'] = (int)($item->total_cost_amount);
            $requestItem['serials'] = $this->generateSerials($requestItem['qty']);
            $items[] = $requestItem;
        }
        $manager = factory(Manager::class)->create();
        $response = $this->actingAs($manager)->postJson('/api/purchases/draft', [
            'items' => $items,
            'vendor_id' => $vendor->id,
            'receiver_id' => Manager::inRandomOrder()->first()->id,
            'vendor_invoice_id' => $this->faker->uuid,
        ]);
        $response
            ->assertOk();
        foreach ($items as $item) {
            $dbItem = Item::find($item['id']);
            $this->assertEquals((int)$item['testing_available_qty'], (int)$dbItem->fresh()->available_qty);
            $this->assertEquals((int)$item['testing_item_cost'], (int)$dbItem->fresh()->cost);
            $this->assertEquals((int)$item['testing_item_total_stock_amount'], (int)$dbItem->fresh()->total_cost_amount);
            foreach ($item['serials'] as $serial) {
                $result = $dbItem->fresh()->serials()->where([
                    ['serial', $serial],
                    ['status', 'in_stock'],
                    ['is_draft',true]
                ])->withoutGlobalScope('draftScope')->get();
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
