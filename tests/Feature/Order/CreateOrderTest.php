<?php
	
	namespace Tests\Feature\Order;
	
	use App\Models\Category;
	use App\Models\Item;
	use App\Models\ShippingAddress;
	use App\Models\ShippingMethod;
	use App\Models\User;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Tests\Base\CreateManager;
	use Tests\TestCase;
	
	class CreateOrderTest extends TestCase
	{
		use CreateManager,RefreshDatabase;
		/**
		 * A basic feature test example.
		 *
		 * @return void
		 */
		public function testCreateOrder_RedirectToOrdersPage()
		{
			$manager = $this->initOrganizationAndManager();
			$category = factory(Category::class)->create([
				'creator_id' => $manager->id,
				'organization_id' => $manager->organization_id,
			]);
			
			$client = factory(User::class)->create([
				'is_client' => true,
				'name' => $this->faker->userName,
				'phone_number' => $this->faker->randomElement(['966556045415','966504956211','24966324018']),
				'organization_id' => 1
			]);
			
			$items = factory(Item::class,10)->create([
				'category_id' => $category->id,
				'creator_id' => $manager->id,
				'organization_id' => $manager->organization_id,
				'is_kit' => false,
				'is_service' => false,
				'available_qty' => $this->faker->numberBetween(50,500),
			]);
			
			
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
