<?php
	
	namespace Tests\Feature\ShippingAddress;
	
	use App\Models\User;
	use Tests\TestCase;
	
	class FetchShippingAddressTest extends TestCase
	{
		
		public function testFetchUserShippingAddress_ReturnList()
		{
			$user = User::inRandomOrder()->first();
			$response = $this->actingAs($user, 'client')->getJson(
				'/api/web/shipping_addresses'
			);
			$response->assertOk();
		}
		
		
		public function testFailFetchUserShippingAddress_UserNotAuthenticated()
		{
			$response = $this->getJson(
				'/api/web/shipping_addresses'
			);
			$response->assertUnauthorized();
		}
	}
