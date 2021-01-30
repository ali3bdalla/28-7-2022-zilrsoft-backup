<?php

namespace Tests\Feature\BackEnd\Inventory\Adjustments;

use App\Models\Item;
use App\Models\Manager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreInventoryAdjustmentTest extends TestCase
{
    // RefreshDatabase
    // use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     * @test
     */
    public function itShouldNormalizeInventory()
    {
        $this->actingAs(Manager::find(1));
        // $this->actingAsManager();
        // $items = factory(Item::class,10)->create([
        //     'organization_id' => 1,
        //     'creator_id' => 1,
        //     'is_need_serial' => false,
        //     'is_kit' => false,
        //     'is_service' => false,
        //     'available_qty' => 50
        // ]);
        $items = Item::find([1717]);

        dd($items );

        $requestItems = [];
        foreach ($items as $item) {
            $item['qty'] = 120;
            $requestItems[] = $item->toArray();
        }


        dd( $requestItems);
        $response = $this->postJson('/api/inventory/adjustments',[
            'items' => $requestItems
        ]);

        $response->dump()->assertStatus(200);

        // foreach ($requestItems as $item) {
        //     $dbEntity = Item::find($item['id']);

        //     $this->assertEquals($dbEntity->available_qty,$item['qty']);
        // }
    }
}