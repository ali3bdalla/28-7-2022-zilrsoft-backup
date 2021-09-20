<?php

namespace Database\Factories;

use App\Dto\OrderDto;
use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    public function setDto(OrderDto $orderDto): OrderFactory
    {
        return $this->state(function () use ($orderDto) {
            return [
                'organization_id' => $orderDto->getOrganizationId(),
                'draft_id' => $orderDto->getDraftInvoiceId(),
                'status' => OrderStatusEnum::issued(),
                'shipping_amount' => $orderDto->getShippingAmount(),
                'payment_method' => $orderDto->getPaymentMethodId(),
                'shipping_address_id' => $orderDto->getShippingAddressId(),
                'net' => $orderDto->getOrderNet(),
                'user_id' => $orderDto->getUserId(),
                'shipping_cost' => $orderDto->getShippingCost(),
                'shipping_weight' => $orderDto->getShippingWeight()
            ];
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'draft_id' => null,
            'status' => null,
            'shipping_amount' => null,
            'payment_method' => null,
            'auto_cancel_at' => Carbon::now()->addMinutes(config('app.store.cancel_unpaid_orders_after', 30)),
            'should_pay_last_notification_at' => Carbon::now()->addMinutes(config('app.store.notify_unpaid_orders_after', 25)),
            'net' => null,
            'user_id' => null,
            "order_secret_code" => rand(10000, 900000),
            'shipping_cost' => null,
            'shipping_weight' => null,
        ];
    }
}
