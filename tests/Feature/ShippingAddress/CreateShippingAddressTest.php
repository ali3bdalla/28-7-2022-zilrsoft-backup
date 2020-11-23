<?php
	
	namespace Tests\Feature\ShippingAddress;
	
	use App\Models\Country;
	use App\Models\User;
	use Illuminate\Foundation\Testing\WithFaker;
	use Tests\TestCase;
	
	class CreateShippingAddressTest extends TestCase
	{
		use WithFaker;
		
		public function testCreateShippingAddress_ReturnAddressInstance()
		{
			$country = Country::inRandomOrder()->first();
			$user = User::inRandomOrder()->first();
			$response = $this->actingAs($user, 'client')->postJson(
				'/api/web/shipping_addresses', [
					'building_number' => $this->faker->buildingNumber,
					'street_name' => $this->faker->streetName,
					'zip_code' => $this->faker->postcode,
					'phone_number' => $this->faker->phoneNumber,
					'city' => $this->faker->city,
					'country_id' => $country->id,
					'first_name' => $this->faker->firstName,
					'last_name' => $this->faker->lastName,
					'description' => $this->faker->address
				]
			);
			$response->assertCreated();
		}
		
		
		public function testFailCreateShippingAddress_FieldsRequired()
		{
			$user = User::inRandomOrder()->first();
			
			$response = $this->actingAs($user, 'client')->postJson(
				'/api/web/shipping_addresses'
			);
			$response
				->assertStatus(422)
				->assertSeeText('phone_number')
				->assertSeeText('city')
				->assertSeeText('country_id')
				->assertSeeText('first_name')
				->assertSeeText('last_name');
		}
	}
