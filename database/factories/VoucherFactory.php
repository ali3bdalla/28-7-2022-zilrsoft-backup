<?php

namespace Database\Factories;

use App\Dto\VoucherDto;
use App\Enums\VoucherTypeEnum;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

class VoucherFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    public function refund(Payment $voucher): VoucherFactory
    {
        $newVoucherType = $voucher->payment_type->equals(VoucherTypeEnum::receipt()) ? VoucherTypeEnum::payment() : VoucherTypeEnum::receipt();
        $voucherDto = new VoucherDto(
            $voucher->account,
            $voucher->userAccount,
            Auth::user(),
            $voucher->user,
            $voucher->amount,
            $newVoucherType,
            'refund ' . $voucher->description
        );
        return $this->setDto($voucherDto)->state(function () use ($voucher) {
            return [
                'refund_payment_id' => $voucher->id,
            ];
        });
    }

    public function setDto(VoucherDto $voucherDto): VoucherFactory
    {
        return $this->state(function () use ($voucherDto) {
            return [
                'organization_id' => $voucherDto->getOrganizationId(),
                'account_id' => $voucherDto->getAccountId(),
                'user_account_id' => $voucherDto->getUserAccountId(),
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
            'creator_id' => null,
            'organization_id' => null,
            'user_id' => null,
            'amount' => null,
            'slug' => "",
            'description' => null,
            'payment_type' => null,
        ];
    }
}
