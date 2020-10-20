<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Country;
use Faker\Generator as Faker;

$factory->define(Country::class, function (Faker $faker) {
	$country_name = $faker->country;
    return [
        //
	    
        'name'=>$country_name,
        'ar_name'=>$country_name,
        // 'status'=>'active',
        // 'currency'=>$faker->currencyCode,
        // 'c_code'=>'0000'
    ];
});
