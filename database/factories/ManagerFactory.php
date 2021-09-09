<?php

namespace Database\Factories;

use App\Models\Manager;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ManagerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Manager::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'branch_id' => 1,
            'user_id' => 1,
            'department_id' => 1,
            'name' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    public function configure(): ManagerFactory
    {
        return $this->afterMaking(function (Manager $manager) {
            if (!$manager->organization_id) {
                $manager->organization_id = (Organization::factory()->create())->id;
            }
            return $manager;
        });
    }
}
