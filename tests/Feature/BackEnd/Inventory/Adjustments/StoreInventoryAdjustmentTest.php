<?php

namespace Tests\Feature\BackEnd\Inventory\Adjustments;

use App\Models\Item;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreInventoryAdjustmentTest extends TestCase
{
    use RefreshDatabase,WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function itShouldNormalizeInventory()
    {
        $this->actingAsManager();
        $items = factory(Item::class,10)->create([
            'organization_id' => 1,
            'creator_id' => 1,
            'is_need_serial' => false,
            'is_kit' => false,
            'is_service' => false,
            'available_qty' => $this->faker->numberBetween(5,100)
        ]);

        $requestItems = [];
        foreach ($items as $item) {
            $item['qty'] = $this->faker->numberBetween(1,599);
            $requestItems[] = $item;
        }


        $response = $this->postJson('/api/inventory/adjustments',[
            'items' => $requestItems
        ]);

        $response->dump()->assertStatus(200);

        foreach ($requestItems as $item) {
            $dbEntity = Item::find($item['id']);

            $this->assertEquals($dbEntity->available_qty,$item['qty']);
        }
    }
}
