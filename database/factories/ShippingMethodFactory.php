<?php
	
	/** @var Factory $factory */
	
	use App\Models\ShippingMethod;
	use Faker\Generator as Faker;
	use Illuminate\Database\Eloquent\Factory;
	
	$factory->define(
		ShippingMethod::class, function(Faker $faker) {
		
		$name = $faker->randomElement(['smsa express', 'DHL']);
		return [
			
			'name' => $name,
			'ar_name' => $name,
			'logo' => $faker->imageUrl(75, 75),
		];
	}
	);
