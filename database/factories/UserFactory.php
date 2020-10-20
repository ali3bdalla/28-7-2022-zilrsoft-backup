<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */


// $table->integer('organization_id');
// $table->string("email_address")->nullable();
// $table->string("password")->nullable();
// $table->integer('creator_id')->default(0);
// $table->string('name');
// $table->string('name_ar')->nullable();
// $table->string('phone_number');
// $table->string('user_slug')->nullable();
// $table->string('country_code')->nullable();
// $table->float('balance', 20, 8)->default(0);
// $table->float('vendor_balance', 20, 8)->default(0);
// $table->boolean('is_supervisor')->default(false);
// $table->boolean('is_manager')->default(false);
// $table->boolean('is_vendor')->default(false);
// $table->boolean('is_client')->default(false);
// $table->boolean('is_supplier')->default(false);
// $table->integer('client_chart_id')->default(0);
// $table->integer('vendor_chart_id')->default(0);
// $table->integer('manager_chart_id')->default(0);
// $table->integer('supplier_chart_id')->default(0);
// $table->boolean('is_system_user')->default(false);
// $table->boolean('can_make_credit')->default(false);
// $table->enum('user_type', ['company', 'individual']);
// $table->enum('user_title', ['mis', 'mr', 'company'])->default('mr');

$factory->define(User::class, function (Faker $faker) {
    return [
        'organization_id' => 1,
        'name' => $faker->name,
        'phone_number' => $faker->phoneNumber,
        'email_address' => $faker->unique()->safeEmail,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'user_type' => 'individual',
        'is_client' => true,
        'is_vendor' => true
    ];
});
