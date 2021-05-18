<?php

/** @var Factory $factory */

use App\Models\City;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(City::class, function (Faker $faker) {
    return [
        //
        'country_id' => 1,
        'is_active' => true,
        'name' => $faker->city,
    ];
});
