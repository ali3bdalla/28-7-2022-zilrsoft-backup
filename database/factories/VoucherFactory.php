<?php

namespace Database\Factories;

use App\Dto\OrderDto;
use App\Dto\VoucherDto;
use App\Enums\OrderStatusEnum;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoucherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    public function setDto(VoucherDto $voucherDto): OrderFactory
    {
        return $this->state(function () use ($voucherDto) {
            return [
                'organization_id' => $voucherDto->getOrganizationId(),
                'creator_id' => $voucherDto->getManagerId(),
                'payment_type' => $voucherDto->getType(),
                'amount' => $voucherDto->getAmount(),
                'description' => $voucherDto->getDescription(),
                'user_id' => $voucherDto->getUserId(),
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
            'creator_id' =>  null,
            'organization_id' =>  null,
            'user_id' =>  null,
            'amount' =>  null,
            'slug' => "",
            'description' =>  null,
            'payment_type' => null,
        ];
    }
}
