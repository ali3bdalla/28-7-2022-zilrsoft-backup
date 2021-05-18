<?php

/** @var Factory $factory */

use App\Models\Filter;
use App\Models\CategoryFilters;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Filter::class, function (Faker $faker) {
    return [
        //
        'creator_id'=>1,
        'name'=>$faker->name,
        'ar_name'=>$faker->name
    ];
});



$factory->define(CategoryFilters::class, function (Faker $faker) {
    return [
        'creator_id'=>1,
        'filter_id'=>rand(1,30),
        'category_id'=>5,
        'organization_id'=>1
    ];
});
