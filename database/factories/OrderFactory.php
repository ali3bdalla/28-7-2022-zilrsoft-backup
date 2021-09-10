<?php

/** @var Factory $factory */

use App\Model;
use App\Models\Order;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(
    Order::class, function (Faker $faker) {
    return [
        'draft_id' => 1,
        'invoice_id' => null,
        'tracking_number' => null,
        'managed_by_id' => null,
        'shipping_method_id' => null,
        'shipping_address_id' => null,
        'auto_cancel_at' => null,
        'should_pay_last_notification_at' => null,
        'cancel_order_code' => null,
        'is_should_pay_notified' => false,
        'net' => 1,
        'status' => 'issued',
    ];
}
);
