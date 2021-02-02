<?php

namespace Tests\Feature\Store\Order;

use App\Models\Category;
use App\Models\Item;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    // use RefreshDatabase;


//     /**
//      * it should return un authenticated status code
//      * @test
//      */
//     public function itShouldReturnUnAuthenticatedStatusCode()
//     {
//         $response = $this->postJson('/api/web/orders');
//         $response->assertUnauthorized();
//     }
//
//     /**
//      * it should return validation exception status code
//      * @test
//      */
//     public function itShouldReturnValidationExceptionStatusCode()
//     {
//         $this->actingAsUser();
//         $response = $this->postJson('/api/web/orders');
//         $response->assertStatus(422);
//     }


    /**
     * it should create order
     * @test
     */
    public function test_user_can_create_new_order()
    {

        $this->actingAs(User::where([['organization_id', 1]])->latest()->first(),'client');
        auth('client')->user()->update([
            'phone_number' => "556045415"
        ]);
        $dbItemsEntities = Item::where([['organization_id', 1], ['available_qty', '>', 1]])->take(5)->get();
        $this->assertAuthenticated();
        $queryItems = [];
        foreach ($dbItemsEntities as $dbItemsEntity) {
            $queryItems[] = [
                'id' => $dbItemsEntity->id,
                'quantity' => 1,
                'available_qty' => $dbItemsEntity->available_qty,
                'weight' => $dbItemsEntity->weight,
                'online_offer_price' => $dbItemsEntity->online_offer_price,
                'shipping_discount' => $dbItemsEntity->shipping_discount,
            ];
        }
        $shippingAddress = factory(ShippingAddress::class)->create(
            [
                'user_id' => auth('client')->user()->id,
                'city_id' => 1,
            ]
        );
        $response = $this->postJson('/api/web/orders', [
            'items' => $queryItems,
            'shipping_method_id' => 6,
            'shipping_address_id' => $shippingAddress->id,
            'payment_method_id' => 'bank_transfer'
        ]);
        $response->dump();

        foreach ($queryItems as $item) {
             $dbitem = Item::find($item['id']);
             $this->assertSame($dbitem->available_qty + (int)$item['quantity'], (int)$item['available_qty']);
         }
//        dd($queryItems);
//         $items = factory(Item::class, 3)->create(
//             [
//                 'category_id' => $category->id,
//                 'creator_id' => 1,
//                 'organization_id' => 1,
//                 'is_kit' => false,
//                 'is_service' => false,
//                 'available_qty' => $this->faker->numberBetween(50, 500),
//                 'online_offer_price' => $this->faker->numberBetween(50, 500),
//                 'shipping_discount' => $this->faker->numberBetween(1, 2),
//             ]
//         );
//         $shippingMethod = factory(ShippingMethod::class)->create();
//         $shippingAddress = factory(ShippingAddress::class)->create(
//             [
//                 'user_id' => auth('client')->user()->id
//             ]
//         );
//
//         $requestItems = [];
//         foreach ($items as $item) {
//             $item->update(['online_price' => $item->price, 'online_offer_price' => $item->price, 'weight' => $this->faker->numberBetween(1, 5), 'shipping_discount' => $this->faker->numberBetween(1, 2)]);
//
//             $requestItems[] = [
//                 'quantity' => 1,
//                 'id' => $item->id,
//                 'available_qty' => $item->available_qty,
//             ];
//         }
//
//         $response = $this->postJson('/api/web/orders', [
//             'items' => $requestItems,
//             'shipping_method_id' => $shippingMethod->id,
//             'shipping_address_id' => $shippingAddress->id,
//             'payment_method_id' => 'Transfer'
//         ]);
//
//         $response->assertRedirect('/web/orders');
//         foreach ($requestItems as $item) {
//             $dbitem = Item::find($item['id']);
//             $this->assertSame($dbitem->available_qty + (int)$item['quantity'], (int)$item['available_qty']);
//         }
    }
}
