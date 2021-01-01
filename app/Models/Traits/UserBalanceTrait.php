<?php

namespace App\Models\Traits;

use App\Models\Account;

trait UserBalanceTrait
{


    public function yearlyUserBalance(Account $account)
    {
        $creditAmount = $this->yearlyUserCreditAmount($account);
        $debitAmount = $this->yearlyUserDebitAmount($account);
        if ($account->type == 'credit')
            return $creditAmount - $debitAmount;
        return $debitAmount - $creditAmount;
    }

    public function yearlyUserCreditAmount($account)
    {
        return $account->transactions()->where(
            [
                ['type','credit'],
                ['user_id',$this->id]
            ]
        )->sum('amount');
    }


    public function yearlyUserDebitAmount($account)
    {
        return $account->transactions()->where(
            [
                ['type','debit'],
                ['user_id',$this->id]
            ]
        )->sum('amount');
    }


}
