<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Account;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'organization_id' => 1,
        'creator_id' => 1,
        'parent_id' => 1,
        'name' =>$faker->name,
        'ar_name' => $faker->name,
        'is_gateway' => $faker->boolean,
        'type' => $faker->randomElement(['credit','debit']),
        'is_system_account' => $faker->boolean
    ];
});
