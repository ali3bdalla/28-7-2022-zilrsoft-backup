<?php

namespace Tests\Feature\Account;

use App\Models\Account;
use Tests\TestCase;

class UpdateBalanceTest extends TestCase
{
    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function toUpdateAccountsBalanceUsingItsTransactions()
    {
        $accounts = Account::all();
        $counter = 0;
        foreach ($accounts as $account)
        {
            $this->assertInstanceOf(Account::class,$account);
            $balance = $account->_updateBalanceUsingTransactions();
            $this->assertIsNumeric($balance);
            $counter++;
        }

        $this->assertCount($counter, $accounts);
    }
}
