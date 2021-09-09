<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Organization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'logo' => $this->faker->imageUrl(),
            'stamp' => $this->faker->imageUrl(),
            'title' => $this->faker->userName,
            'title_ar' => $this->faker->userName,
            'description_ar' => $this->faker->userName,
            'description' => $this->faker->userName,
            'city' => $this->faker->userName,
            'city_ar' => $this->faker->userName,
            'country_id' => 1,
            'type_id' => 1,
            'phone_number' => $this->faker->phoneNumber,
            'cr' => $this->faker->uuid,
            'vat' => $this->faker->creditCardNumber
        ];
    }
}
