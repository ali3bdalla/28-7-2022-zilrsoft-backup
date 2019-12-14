<?php
	
	namespace Tests\Feature;
	
	use App\Country;
	use App\Type;
	use Illuminate\Foundation\Testing\WithFaker;
	use Tests\TestCase;

//
	class OrganizationTest extends TestCase
	{
		use WithFaker;

//
		
		/**
		 * A basic feature test example.
		 *
		 * @test
		 * @return void
		 */
		
		public function toCreate()
		{
			$response = $this->json('post',route('management.register'),[
				'org_title' => $this->faker->name,
				'org_title_ar' => $this->faker->name,
				'org_city' => $this->faker->city,
				'org_city_ar' => $this->faker->city,
				'org_address' => $this->faker->address,
				'org_address_ar' => $this->faker->address,
				'org_phone_number' => $this->faker->phoneNumber,
				'org_country_id' => 1,
				'org_business_type' => 1,
				'org_type' => config('data.types.establishment.en'),
				'org_cr' => $this->faker->buildingNumber,
				'org_vat' => $this->faker->buildingNumber,
				'org_description' => $this->faker->sentence,
				'org_description_ar' => $this->faker->sentence,
				'name' => $this->faker->name,
				'name_ar' => $this->faker->name,
				'email' => 'admin@admin.com',
				'phone_number' => $this->faker->phoneNumber,
				'password' => 'password',
				'password_confirmation' => 'password'
			
			]);
			
			//$response->dump();
			
			$response->assertStatus(302);
		}
		
	}
