<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Account::class;

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
}
