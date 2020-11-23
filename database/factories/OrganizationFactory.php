<?php
	
	/** @var Factory $factory */
	
	use App\Models\Country;
	use App\Models\Organization;
	use App\Models\Type;
	use Faker\Generator as Faker;
	use Illuminate\Database\Eloquent\Factory;
	
	$factory->define(
		Organization::class, function(Faker $faker) {
		return [
			'logo' => $faker->imageUrl(),
			'stamp' => $faker->imageUrl(),
			'title' => $faker->userName,
			'title_ar' => $faker->userName,
			'description_ar' => $faker->userName,
			'description' => $faker->userName,
			'city' => $faker->userName,
			'city_ar' => $faker->userName,
			'country_id' => \factory(Country::class)->create()->id,
			'type_id' => \factory(Type::class)->create()->id,
			'phone_number' => $faker->phoneNumber,
			'cr' => $faker->uuid,
			'vat' => $faker->creditCardNumber
		];
	}
	);
	
	//
	//	$table->bigIncrements('id');
	//	$table->string('title');
	//	$table->string('title_ar');
	//	$table->string('city');
	//	$table->string('city_ar');
	//	$table->text('description')->nullable();
	//	$table->text('description_ar')->nullable();
	//	$table->enum('type',["individual","government","corporation",'establishment'])->default('individual');
	//	$table->integer('country_id');
	//	$table->integer('type_id');
	//	$table->integer('supervisor_id')->nullable();
	//	$table->string('logo')->nullable();
	//	$table->string('address')->nullable();
	//	$table->string('address_ar')->nullable();
	//	$table->string('phone_number')->nullable();
	//	$table->string('stamp')->nullable();
	//	$table->string('vat');
	//	$table->string('cr');