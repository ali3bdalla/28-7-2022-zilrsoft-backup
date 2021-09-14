<?php

namespace Tests;

use App\Dto\ManagerDto;
use App\Dto\OrganizationDto;
use App\Dto\UserDto;
use App\Models\Category;
use App\Models\Country;
use App\Models\Item;
use App\Models\Manager;
use App\Models\Organization;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Propaganistas\LaravelPhone\PhoneNumber;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;
    use WithFaker;

    public function makeInvoiceData(): array
    {
        $user = $this->createUser();
        $category = Category::factory()->state(function () use ($user) {
            return [
                'creator_id' => $user->creator_id,
                'organization_id' => $user->organization_id,
            ];
        })->create();
        $items = Item::factory()->count($this->faker->numberBetween(1,10))->state(function () use ($category) {
            return [
                'available_qty' => 15000,
                'is_kit' => false,
                'is_available_online' => true,
                'creator_id' => $category->creator_id,
                'organization_id' => $category->organization_id,
                'category_id' => $category->id,
            ];
        })->create();
        return [
            $user,
            $items->map(function (Item $item) {
                return [
                    'id' => $item->id,
                    'quantity' => $this->faker->randomFloat(2, 100, 1000),
                    'discount' => $this->faker->randomFloat(2, 10, 90)
                ];
            })->toArray()
        ];
    }

    public function createUser(array $attributes = []): User
    {
        $attributes = collect($attributes);
        $userDto = new UserDto(
            $this->createManager(),
            PhoneNumber::make("966324018", ['SD']),
            $attributes->get('first_name', $this->faker->firstName),
            $attributes->get('last_name', $this->faker->lastName),
            $attributes->get('password', $this->faker->password),
        );
        return User::factory()->setDto($userDto)->create();
    }

    public function createManager(array $attributes = []): Manager
    {
        $attributes = collect($attributes);
        return Manager::factory()->setDto(new ManagerDto(
            Organization::factory()->setDto(new OrganizationDto(
                $this->faker->company,
                $this->faker->city,
                $this->faker->sentence,
                $this->faker->creditCardNumber,
                $this->faker->creditCardNumber,
                PhoneNumber::make($this->faker->phoneNumber),
                Type::factory()->create(),
                Country::factory()->create(),
            ))->create(),
            $attributes->get('name', $this->faker->userName),
            $attributes->get('email', $this->faker->safeEmail),
            $attributes->get('password', $this->faker->password),
        ))->create();
    }
}
