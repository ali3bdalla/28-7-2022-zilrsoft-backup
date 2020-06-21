<?php

namespace Tests\Feature\Transaction;

use App\Account;
use App\AccountStatistic;
use App\Http\Middleware\VerifyCsrfToken;
use App\Transaction;
use App\TransactionsContainer;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTransactionsTest extends TestCase
{
    use WithFaker;
    /**
     * Send Request to delete  transaction container and it's transactions
     *
     * @test
     * @return void
     */
    public function toDeleteTransactionsEntitiesAndContainer()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        auth()->loginUsingId(1);
        $this->assertAuthenticated();

        $transactionContainer = TransactionsContainer::inRandomOrder()->first();
        $transaction = $transactionContainer->transactions()
            ->where('debitable_type','LIKE','%Account%')
            ->where('creditable_type','LIKE','%Account%')
            ->inRandomOrder()->first();

        while ($transaction == null)
        {
            $transactionContainer = TransactionsContainer::inRandomOrder()->first();
            $transaction = $transactionContainer->transactions()
                ->where('debitable_type','LIKE','%Account%')
                ->where('creditable_type','LIKE','%Account%')
                ->inRandomOrder()->first();
        }
        $this->assertNotNull($transaction);
        $this->assertInstanceOf(Transaction::class,$transaction);
        if($transaction->_isDebitAbleAccount())
        {
            $account = $transaction->_getDebitAbleAccount();
            if($account->_isDebit())
            {
                $operator = 'sub';
            }else
            {
                $operator = 'add';
            }
        }else
        {
            $account = $transaction->_getCreditableAccount();
            if($account->_isCredit())
            {
                $operator = 'add';
            }else
            {
                $operator = 'sub';
            }
        }
        $this->assertNotNull($account);
        $this->assertInstanceOf(Account::class,$account);
        $accountCurrentAmount = $account->statistics->total_amount;
        $this->assertIsNumeric($accountCurrentAmount);
        $this->assertGreaterThanOrEqual(0,$accountCurrentAmount);
        $response = $this->delete(route('accounting.transactions.destroy',$transaction->id),[],
            [
                'accept' => 'application/json'
            ]);

        $response->assertOk();
        
        if($operator == 'add')
            $expectedFirstAccountAmount =  $accountCurrentAmount + $transaction->amount;
        else
            $expectedFirstAccountAmount =  $accountCurrentAmount - $transaction->amount;

        $freshFirstCreditAccountAmount = $account->statistics->fresh()->getOriginal("total_amount");
        $this->assertEquals($expectedFirstAccountAmount,$freshFirstCreditAccountAmount);
    }
}
