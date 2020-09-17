<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Type;
use Faker\Generator as Faker;

$factory->define(App\Models\Type::class, function (Faker $faker) {
    return [
        //
        'name'=>$faker->sentence

    ];
});
