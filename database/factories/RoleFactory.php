<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
	$name = $faker->unique()->name;
    return [
        //
        'name'=>$name,
        'slug'=>str_slug($name)
    ];
});
