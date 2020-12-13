<?php

/** @var Factory $factory */

use App\Models\Country;
use App\Models\Organization;
use App\Models\Type;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(
    Organization::class, function (Faker $faker) {
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
