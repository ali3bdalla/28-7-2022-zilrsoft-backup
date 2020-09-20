<?php

namespace App\Attributes;

use App\Models\Account;

trait AccountAttributes
{

    public function getIsExpandedAttribute()
    {
        return false;
    }

    public function getLabelAttribute()
    {
        return $this->locale_name;
    }

    public function getCurrentAmountAttribute()
    {
        $totalDebitAmount = Account::whereIn('id', $this->getChildrenIncludeMe())->sum('total_debit_amount');
        $totalCreditAmount = Account::whereIn('id', $this->getChildrenIncludeMe())->sum('total_credit_amount');
        if ($this->_isCredit()) {
            return (float) ($totalCreditAmount - $totalDebitAmount);
        }
        return (float) ($totalDebitAmount - $totalCreditAmount);
    }

    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar')) {
            return $this->ar_name;
        } else {
            return $this->name;
        }
    }

//     public function _getCurrentBalanceUsingTransaction()
    //     {
    //         if ($this->_isStock()) {
    //             $balance = $this->_getStockBalanceUsingTransactions();
    //         } else {
    //             $balance = $this->_getAccountBalanceUsingTransactions();
    //         }

//         return $balance;
    //     }

//     public function _isStock()
    //     {
    //         return $this->slug == 'stock';
    //     }

//     public function _getStockBalanceUsingTransactions()
    //     {
    //         $creditAmount = $this->moneyFormatter($this->_getStockCreditAmount());
    //         $debitAmount = $this->moneyFormatter($this->_getStockDebitAmount());
    //         $this->_updateCreditAndDebitAmountForAccount($creditAmount, $debitAmount);
    //         return $debitAmount - $creditAmount;
    //     }

//     public function _getStockCreditAmount()
    //     {
    //         return Transaction::where('creditable_type', 'App\Models\Item')->sum('amount');
    //     }

//     public function _getStockDebitAmount()
    //     {
    //         return Transaction::where('debitable_type', 'App\Models\Item')->sum('amount');

//     }

//     public function _updateCreditAndDebitAmountForAccount($creditAmount, $debitAmount)
    //     {

//         if ($this->statistics == null) {
    //             $credit = $this->statistics()->create([
    //                 'credit_amount' => (float) $creditAmount,
    //                 'debit_amount' => (float) ($debitAmount),
    //             ]);
    //         } else {
    //             $credit = $this->statistics()->update([
    //                 'credit_amount' => (float) ($creditAmount),
    //                 'debit_amount' => (float) ($debitAmount),
    //             ]);
    //         }

//         return $credit;

//     }

//     public function _getAccountBalanceUsingTransactions($creditAmount = null, $debitAmount = null)
    //     {
    //         if ($creditAmount === null && $debitAmount === null) {
    //             $whereStatements = $this->_getAdditionalWhereStatement();
    //             $creditAmount = $this->moneyFormatter($this->_getCreditTransactionsAmount($whereStatements));
    //             $debitAmount = $this->moneyFormatter($this->_getDebitTransactionsAmount($whereStatements));
    //         }

//         // update credit and debit amount
    //         $this->_updateCreditAndDebitAmountForAccount($creditAmount, $debitAmount);

//         if ($this->_isCredit()) {
    //             return (float) $creditAmount - (float) $debitAmount;
    //         }

//         return (float) $debitAmount - (float) $creditAmount;
    //     }

    public function updateAccountBalanceUsingPipeline()
    {
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;
        foreach ($this->transactions as $transaction) {
            if ($transaction->type == 'credit') {
                $totalCreditAmount += (float) $transaction->amount;
            } else {
                $totalDebitAmount += (float) $transaction->amount;

            }
        }

        $this->forceFill([
            'total_credit_amount' => $totalCreditAmount,
            'total_debit_amount' => $totalDebitAmount,

        ]);

        return $this->getCurrentAmountAttribute();
    }

    public function _isDebit()
    {
        return $this->type == 'debit';
    }

    public function _isCredit()
    {
        return $this->type == 'credit';
    }

}
