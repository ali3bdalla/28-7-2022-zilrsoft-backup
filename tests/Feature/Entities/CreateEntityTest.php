<?php

namespace Tests\Feature\Entities;

use App\Models\Account;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateEntityTest extends TestCase
{
    use WithFaker;
    /**
     * Send Request to create new transaction
     *
     * @test
     * @return void
     */
    public function test_create_entity()
    {



        $totalTransactionAmount = 20;

        $firstCreditAccount = Account::where('type','credit')->inRandomOrder()->first();
        $firstDebitAccount = Account::where([['id','!=',$firstCreditAccount->id],['type','debit'],['slug','clients']])->inRandomOrder()->first();
        $secondDebitAccount = Account::where('type','debit')->whereNotIn('id',[$firstCreditAccount->id,$firstDebitAccount->id])->inRandomOrder()->first();

        $user = Manager::inRandomOrder()->first();
        $response = $this->actingAs($user)->postJson('/api/entities',[
            'transactions' => [
                [
                    'id'=> $firstCreditAccount->id,
                    'amount' => $totalTransactionAmount ,
                    'type' => 'credit',
                ],
                [
                    'id'=> $firstDebitAccount->id,
                    'amount' => $totalTransactionAmount / 2,
                    'client_id' => User::inRandomOrder()->where('is_client' ,true)->first()->id,
                    'type' => 'debit',
                ],
                [
                    'id'=> $secondDebitAccount->id,
                    'amount' => $totalTransactionAmount / 2,
                    'type' => 'debit',
                ]
            ],
            'description' => $this->faker->sentence,
            "amount" => $totalTransactionAmount
        ],
        [
            'accept' => 'application/json'
        ]);

        $response->dump()->assertCreated();
       
    }
}
