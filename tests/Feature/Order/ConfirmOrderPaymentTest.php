<?php
	
	namespace Tests\Feature\Order;
	
	use App\Models\Category;
	use App\Models\Item;
	use App\Models\Manager;
	
	use App\Models\Order;
	use App\Models\ShippingAddress;
	use App\Models\ShippingMethod;
	use App\Models\User;
	use Tests\TestCase;
	
	class ConfirmOrderPaymentTest extends TestCase
	{
		private $userFactory = false;
		
		/**
		 * A basic feature test example.
		 *
		 * @return void
		 */
		public function testCreateOrder_RedirectToOrdersPage()
		{
			if($this->userFactory) {
				$manager = $this->initOrganizationAndManager();
				$category = factory(Category::class)->create(
					[
						'creator_id' => $manager->id,
						'organization_id' => $manager->organization_id,
					]
				);
				
				
				$items = factory(Item::class, 10)->create(
					[
						'category_id' => $category->id,
						'creator_id' => $manager->id,
						'organization_id' => $manager->organization_id,
						'is_kit' => false,
						'is_service' => false,
						'available_qty' => $this->faker->numberBetween(50, 500),
					]
				);
				$client = factory(User::class)->create(
					[
						'is_client' => true,
						'name' => $this->faker->userName,
						'phone_number' => '249966324018', //249966324018
						'organization_id' => 1
					]
				);
				
			} else {
				$manager = Manager::find(1);
				$items = Item::where(
					[
						['is_kit', false],
						['is_service', false],
						['available_qty', ">", 0],
					
					]
				)->inRandomOrder()->take(5)->get();
				
				$client = User::where(
					[
						['is_client', true]
					]
				)->inRandomOrder()->first();
				
				$client->update(
					[
						'phone_number' => '249966324018',
						'organization_id' => 1,
						'creator_id' => $manager->id
					]
				);
				
				
			}
			
			
			$shippingMethod = factory(ShippingMethod::class)->create();
			$shippingAddress = factory(ShippingAddress::class)->create(
				[
					'user_id' => $client->id
				]
			);
			
			$requestItems = [];
			foreach($items as $item) {
				$item->update(['online_price' => $item->price]);
				
				$requestItems[] = [
					'quantity' => 1,
					'id' => $item->id,
					'available_qty' => $item->available_qty,
				];
			}
			
			
			$response = $this->actingAs($client, 'client')->postJson(
				'/api/web/orders', [
					'items' => $requestItems,
					'shipping_method_id' => $shippingMethod->id,
					'shipping_address_id' => $shippingAddress->id
				]
			);
			foreach($requestItems as $item) {
				$dbitem = Item::find($item['id']);
				$this->assertEquals($dbitem->available_qty + $item['quantity'], $item['available_qty']);
			}
			
			$response->assertRedirect('/web/orders');
			
			
			$order = Order::orderBy('id', 'desc')->first();
			
			$this->actingAs(Manager::find(1));
			$response2 = $this->putJson('/api/orders/' . $order->id);
			$response2->dump();
		}
		
	}