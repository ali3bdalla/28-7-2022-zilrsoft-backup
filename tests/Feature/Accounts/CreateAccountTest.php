<?php

namespace Tests\Feature\Accounts;

use App\Models\Account;
use App\Models\Manager;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateAccountTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_account()
    {
        $manager = factory(Manager::class)->create();
        $parent = factory(Account::class)->create([
            'parent_id' => 0
        ]);
        $response = $this->actingAs($manager)->postJson('/api/accounts',[
            'name' => $this->faker->name,
            'ar_name' => $this->faker->name,
            'parent_id' => $parent->id,
            'account_type' => $this->faker->randomElement(['credit','debit'])
        ]);

        $response
        ->assertCreated();
    }
}
