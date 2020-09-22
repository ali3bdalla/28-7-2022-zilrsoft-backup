<?php

/** @var Factory $factory */

use App\Models\Item;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Item::class, function (Faker $faker) {

    $vat = 15;
    $priceWithTax = $faker->numberBetween(20, 500);

    $price = $priceWithTax / 1.15;//50
    return [

        'organization_id' => 1,
        'creator_id' => 1,
        'category_id' => 1,
        'warranty_subscription_id' => 0,
        'name' => $faker->name,
        'ar_name' => $faker->name,
        'barcode' => uniqid(),
        'is_kit' => false,
        'is_fixed_price' => true,
        'is_has_vts' => true,
        'is_has_vtp' => true,
        'is_need_serial' => false,
        'is_service' => false,
        'is_expense' => false,
        'is_available_online' => false,
        'expense_vendor_id' => 2,
        'price' => $price,
        'price_with_tax' => $priceWithTax,
        'last_p_price' => 0,
        'online_price' => 0,
        'cost' => 0,
        'vts' => $vat,
        'vtp' => $vat,
        'vts_for_print' => $vat,
        'vtp_for_print' => $vat,
        'available_qty' => 0,
        'status' => 'active',
    ];
});
