<?php

namespace Tests\Feature\Purchases;

use App\Models\Invoice;
use App\Models\Item;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateReturnPurchaseTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_return_purchase()
    {

        $purchaseInvoice = Invoice::where([
            ['invoice_type', 'purchase'],
            ['is_draft', false]
        ])->inRandomOrder()->first();
        $this->assertInstanceOf(Invoice::class, $purchaseInvoice);
        foreach ($purchaseInvoice->items as $item) {
            $requestItem = [];
            $requestItem['id'] = $item->id;
            $requestItem['returned_qty'] = $this->faker->numberBetween(1, $item->qty);
            $requestItem['discount'] = $item->discount;
            if ($item->item->is_need_serial) {
                $requestItem['serials'] = $item->item->serials()->where('purchase_id', $item->invoice_id)->take($requestItem['returned_qty'])->pluck('serial')->toArray();
            }
            $requestItem['discount'] = $item->discount;
            $requestItem['testing_available_qty'] = (int)$item->item->available_qty;
            $requestItem['testing_item_id'] = (int)$item->item_id;
            $requestItem['testing_item_cost'] = $item->item->cost;
            $requestItem['testing_item_total_stock_amount'] = ($item->item->available_qty * $item->item->cost);
            $requestItem['testing_subtotal_amount'] = ($item->subtotal / $item->qty) * $requestItem['returned_qty'];


            $requestItem['testing_subtotal'] = ((float)$item->price * (float)$requestItem['returned_qty']);
            $requestItem['testing_total_credit_amount'] = $item->item->total_credit_amount;
            $requestItem['testing_total_debit_amount'] = $item->item->total_debit_amount;


            $items[] = $requestItem;
        }
        $manager = factory(Manager::class)->create();
        $response = $this->actingAs($manager)->patchJson('/api/purchases/' . $purchaseInvoice->id, [
            'items' => $items,
        ]);
        $response
//            ->dump()
            ->assertOk();
        foreach ($items as $item) {
            $dbItem = Item::find($item['testing_item_id']);


            $this->assertEquals(roundMoney((float)$item['testing_total_credit_amount'] + (float)$item['testing_subtotal']),roundMoney($dbItem->total_credit_amount));
            $this->assertEquals(roundMoney($item['testing_total_debit_amount']),roundMoney($dbItem->total_debit_amount));


            $this->assertEquals((int)$item['testing_available_qty'] - (int)$item['returned_qty'], (int)$dbItem->fresh()->available_qty);
            $this->assertEquals(roundMoney((float)((((float)$item['testing_item_total_stock_amount'] - (float)$item["testing_subtotal_amount"])) / $dbItem->available_qty)), roundMoney($dbItem->cost));
        }
    }


}
