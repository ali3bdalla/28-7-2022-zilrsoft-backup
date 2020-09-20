<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Organization;
use Faker\Generator as Faker;

$factory->define(Organization::class, function (Faker $faker) {
    return [
        'title' => $faker->company,
        'title_ar' => $faker->company,
        'city' => $faker->city,
        'city_ar' => $faker->city,
        'country_id' =>1,
        'type_id' => 1,
        'vat' => 15,
        'cr' => $faker->sentence,
    ];
});
