<?php
	
	/** @var Factory $factory */
	
	use App\Model;
	use App\Models\Country;
	use App\Models\ShippingAddress;
	use App\Models\User;
	use Faker\Generator as Faker;
	use Illuminate\Database\Eloquent\Factory;
	
	$factory->define(
		ShippingAddress::class, function(Faker $faker) {
		return [
			'user_id' => User::latest()->first()->id,
//			'country_id' => Country::inRandomOrder()->first()->id,
			'building_number' => $faker->buildingNumber,
			'street_name' => $faker->streetName,
			'zip_code' => $faker->postcode,
			'phone_number' => $faker->phoneNumber,
			'city_id' => 1,
			'first_name' => $faker->firstName,
			'last_name' => $faker->lastName,
			'description' => $faker->address
		];
	}
	);
