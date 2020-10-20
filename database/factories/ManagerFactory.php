<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Manager;
use App\Models\User;
use Faker\Generator as Faker;


$factory->define(Manager::class, function (Faker $faker) {
    $user = factory(User::class)->create([
        'is_manager' => true
    ]);
    return [
        'branch_id' => 1,
        'department_id' => 1,
        'organization_id' =>  $user->organization_id,
        'user_id' => $user->id,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        
    ];
});

