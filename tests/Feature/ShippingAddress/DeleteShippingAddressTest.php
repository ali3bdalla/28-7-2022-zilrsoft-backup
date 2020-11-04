<?php
	
	namespace Tests\Feature\ShippingAddress;
	
	use App\Models\ShippingAddress;
	use Tests\TestCase;
	
	class DeleteShippingAddressTest extends TestCase
	{
		
		public function testDeleteShippingAddress_ReturnSuccessStatusCode()
		{
			$shippingAddress = factory(ShippingAddress::class)->create();
			$response = $this->actingAs($shippingAddress->user, 'client')->deleteJson("/api/web/shipping_addresses/{$shippingAddress->id}");
			$response->assertStatus(200);
		}
	}
