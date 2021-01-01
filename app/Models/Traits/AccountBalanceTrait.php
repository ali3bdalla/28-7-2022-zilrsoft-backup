<?php

namespace App\Models\Traits;

use App\Models\Account;

trait AccountBalanceTrait
{



    public function yearlyAccountBalance()
    {
        $creditAmount = $this->yearlyAccountCreditAmount();
        $debitAmount = $this->yearlyAccountDebitAmount();
        if ($this->type == 'credit')
            return $creditAmount - $debitAmount;
        return $debitAmount - $creditAmount;
    }

    public function yearlyAccountCreditAmount()
    {
        return $this->snapshots()->sum('credit_amount');
    }


    public function yearlyAccountDebitAmount()
    {
        return $this->snapshots()->sum('debit_amount');
    }


    public function yearlyNestedAccountBalance()
    {
        
        $accounts = $this->getChildrenIncludeMe();

        $balance = 0;
        foreach ($accounts as $accountId) {
            $account = Account::find($accountId);
            if ($account)
                $balance += (float) $account->yearlyAccountBalance();
        }

        return $balance;
    }
}
