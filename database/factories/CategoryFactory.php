<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Models\User;
use App\Models\Organization;

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
