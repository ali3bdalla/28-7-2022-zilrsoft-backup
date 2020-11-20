<?php
	
	/** @var Factory $factory */
	
	use App\Models\TransactionsContainer;
	use Faker\Generator as Faker;
	use Illuminate\Database\Eloquent\Factory;
	
	$factory->define(
		TransactionsContainer::class, function(Faker $faker) {
		return [
			//
			'description' => $faker->sentence,
			'amount' => $faker->numberBetween(1, 500),
			'is_pending' => $faker->boolean,
			'invoice_id' => 0,
			'organization_id' => 0,
			'creator_id' => 0,
		];
	}
	);
