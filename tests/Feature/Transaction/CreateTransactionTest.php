<?php

namespace Tests\Feature\Transaction;

use App\Models\Account;
use App\Http\Middleware\VerifyCsrfToken;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTransactionTest extends TestCase
{
    use WithFaker;
    /**
     * Send Request to create new transaction
     *
     * @test
     * @return void
     */
    public function toCreateNewTransactionEntities()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        auth()->loginUsingId(1);
        $this->assertAuthenticated();
        $totalTransactionAmount = 20;
        $firstCreditAccount = Account::where('type','credit')->inRandomOrder()->first();
        $firstDebitAccount = Account::where([['id','!=',$firstCreditAccount->id],['type','debit'],['slug','clients']])->inRandomOrder()->first();
        $secondDebitAccount = Account::where('type','debit')->whereNotIn('id',[$firstCreditAccount->id,$firstDebitAccount->id])->inRandomOrder()->first();
        $this->assertNotEquals($firstCreditAccount->id,$firstDebitAccount->id);
        $this->assertNotEquals($secondDebitAccount->id,$firstDebitAccount->id);
        $this->assertNotEquals($firstCreditAccount->id,$secondDebitAccount->id);
        $response = $this->post(route('accounting.transactions.store'),[
            'transactions' => [
                [
                    'id'=> $firstCreditAccount->id,
                    'credit_amount' => $totalTransactionAmount ,
                    'is_credit' => true,
                    'debit_amount' => 0
                ],
                [
                    'id'=> $firstDebitAccount->id,
                    'debit_amount' => $totalTransactionAmount / 2,
                    'client_id' => User::inRandomOrder()->where('is_client' ,true)->first()->id,
                    'is_credit' => false,
                    'credit_amount' => 0
                ],
                [
                    'id'=> $secondDebitAccount->id,
                    'debit_amount' => $totalTransactionAmount / 2,
                    'is_credit' => false,
                    'credit_amount' => 0
                ]
            ],
            'description' => $this->faker->sentence,
            "amount" => $totalTransactionAmount
        ],
        [
            'accept' => 'application/json'
        ]);

//        $response->dump();
       
    }
}
