<?php
	
	namespace Tests\Feature\Order;
	
	use App\Models\Item;
	use App\Models\ShippingAddress;
	use App\Models\ShippingMethod;
	use App\Models\User;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Tests\TestCase;
	
	class CreateOrderTest extends TestCase
	{
		/**
		 * A basic feature test example.
		 *
		 * @return void
		 */
		public function testCreateOrder_RedirectToOrdersPage()
		{
			$client = User::where('is_client',true)->inRandomOrder()->first();
			$shippingMethod = factory(ShippingMethod::class)->create();
			
			$shippingAddress = factory(ShippingAddress::class)->create(
				[
					'user_id' => $client->id
				]
			);
			
			
			$items = Item::where(
				[
					['is_kit', false],
					['is_service', false],
					['available_qty', '>', 0],
				]
			)->inRandomOrder()->take(5)->get();
			
			$requestItems = [];
			foreach($items as $item) {
				$item->update(['online_price' => $item->price]);
				
				$requestItems[] = [
					'quantity' => 1,
					'id' => $item->id
				];
			}
			$response = $this->actingAs($client,'client')->postJson(
				'/api/web/orders', [
					'items' => $requestItems,
					'shipping_method_id' => $shippingMethod->id,
					'shipping_address_id' => $shippingAddress->id
				]
			);
			
			$response->dump()->assertRedirect('/web/orders');
		}
	}
