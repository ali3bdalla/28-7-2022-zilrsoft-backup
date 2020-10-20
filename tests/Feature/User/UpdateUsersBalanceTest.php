<?php

namespace Tests\Feature\User;

use App\Models\Account;
use App\Models\User;
use Tests\TestCase;

class UpdateUsersBalanceTest extends TestCase
{
    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function toCheckClientsBalance()
    {
        $users = User::where('is_client',true)->withTrashed()->get();
        $clientsAccount = Account::getUsingSlug('clients');
        $clientsTotalBalance = 0;
        $this->assertInstanceOf(Account::class,$clientsAccount);
        $this->assertIsIterable($users);
        foreach ($users as $user)
        {
            $clientsIds[] = $user->id;
            $balance = $user->_getUserBalanceUsingTransactions($clientsAccount,'client');
            $this->assertIsNumeric($balance);
            $clientsTotalBalance+=$balance;
            $updatedBalance = $user->_updateAndGetUserBalanceUsingTransaction($clientsAccount,'client');
            $this->assertEquals($balance,$updatedBalance);
            $this->assertEquals(floor($balance),floor($user->fresh()->_getClientBalance()));
        }


        $isClientsBalancesMatchAccount = floor($clientsTotalBalance) ==  floor($clientsAccount->_getCurrentBalanceUsingTransaction());
        if($isClientsBalancesMatchAccount)
            $clientsAccount->_updateBalanceUsingTransactions();
        $this->assertTrue($isClientsBalancesMatchAccount);

    }



    /**
     * A basic feature test example.
     * @test
     * @return void
     */
    public function toCheckVendorsBalance()
    {
        $users = User::where('is_vendor',true)->withTrashed()->get();


        $vendorsAccount = Account::getUsingSlug('vendors');
        $vendorsTotalBalance = 0;
        $this->assertInstanceOf(Account::class,$vendorsAccount);
        $this->assertIsIterable($users);
        foreach ($users as $user)
        {
            $balance = $user->_getUserBalanceUsingTransactions($vendorsAccount,'vendor');
            $this->assertIsNumeric($balance);
            $vendorsTotalBalance+=$balance;
            $updatedBalance = $user->_updateAndGetUserBalanceUsingTransaction($vendorsAccount,'vendor');
            $this->assertEquals($balance,$updatedBalance);
            $this->assertEquals(floor($balance),floor($user->fresh()->_getVendorBalance()));
        }


        $isVendorsBalancesMatchAccount = floor($vendorsTotalBalance) == floor($vendorsAccount->_getCurrentBalanceUsingTransaction());
        if($isVendorsBalancesMatchAccount)
            $vendorsAccount->_updateBalanceUsingTransactions();

        $this->assertTrue($isVendorsBalancesMatchAccount);


    }

}
