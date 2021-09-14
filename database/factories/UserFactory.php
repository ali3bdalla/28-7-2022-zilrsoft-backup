<?php

namespace Database\Factories;

use App\Dto\UserDto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    public function setDto(UserDto $userDto): UserFactory
    {
        return $this->state(function () use ($userDto) {
            return [
                'creator_id' => $userDto->getManager()->id,
                'organization_id' => $userDto->getManager()->organization_id,
                'is_client' => $userDto->isClient(),
                'is_vendor' => $userDto->isVendor(),
                'name' => $userDto->getName(),
                'name_ar' => $userDto->getName(),
                'phone_number' => $userDto->getPhoneNumber(),
                'country_code' => $userDto->getCountryCode(),
                'password' => Hash::make($userDto->getPassword()),
                'user_type' => $userDto->getUserType(),
                'user_title' => $userDto->getUserTitle(),
                'verification_code' => $userDto->getVerificationCode(),
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
            'is_client' => null,
            'is_vendor' => null,
            'name' => null,
            'name_ar' => null,
            'phone_number' => null,
            'country_code' => null,
            'password' => null,
            'user_type' => null,
            'user_title' => null,
            'verification_code' => null,
        ];
    }
}
