<?php

namespace Database\Factories;

use App\Base\BaseFactory;
use App\Dto\AccountDto;
use App\Models\Account;
use App\Models\Order;

class AccountFactory extends BaseFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;


    public function setDto(AccountDto $accountDto): AccountFactory
    {
        return $this->state(function () use ($accountDto) {
            return [
                'organization_id' => $accountDto->getOrganizationId(),
                'creator_id' => $accountDto->getManagerId(),
                'parent_id' => $accountDto->getParentId(),
                'name' => $accountDto->getName(),
                'ar_name' => $accountDto->getArName(),
                'is_gateway' => $accountDto->isGateway(),
                'type' => $accountDto->getType(),
                'slug' => $accountDto->getAccountSlug(),
                'is_system_account' => $accountDto->isSystemAccount(),
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
            'organization_id' => 1,
            'creator_id' => 1,
            'parent_id' => 1,
            'name' => $this->faker->name,
            'ar_name' => $this->faker->name,
            'is_gateway' => $this->faker->boolean,
            'type' => $this->faker->randomElement(['credit', 'debit']),
            'is_system_account' => $this->faker->boolean
        ];
    }

    public function clone(Order $order)
    {
        // TODO: Implement clone() method.
    }
}
