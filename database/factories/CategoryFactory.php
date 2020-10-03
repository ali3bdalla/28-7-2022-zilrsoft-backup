<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;

use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        //
        'organization_id'=>1,
        'name'=>$faker->name,
        'ar_name'=>$faker->name,
        'creator_id'=>1,
    ];
});
