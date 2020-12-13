<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\City::class, function (Faker $faker) {
    return [
        //
        'country_id' => 1,
        'is_active' => true,
        'name' => $faker->city,
    ];
});
