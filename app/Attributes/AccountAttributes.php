<?php


namespace App\Attributes;

use App\Account;
use App\AccountStatistic;
use App\Transaction;


trait AccountAttributes
{

    public static function getUsingSlug($slug, $is_system_account = true)
    {
        return (new static())->toGetAccountWithSlug($slug, $is_system_account);
    }

    public static function toGetAccountWithSlug($slug, $is_system_account = true)
    { 
        return Account::where([['slug', $slug], ['is_system_account', $is_system_account]])->first();
    }

    public function getTrialBalanceData()
    {

        if (!is_null($this->statistics)) {
            $debitAmount = $this->statistics->debit_amount;
            $creditAmount = $this->statistics->credit_amount;
        } else {
            $debitAmount = $this->_getDebitTransactionsAmount();
            $creditAmount = $this->_getCreditTransactionsAmount();
        }


        if ($this->_isCredit()) {
            $accountTotalAmount = $creditAmount - $debitAmount;
            $accountCreditBalance = $accountTotalAmount > 0 ? $accountTotalAmount : 0;
            $accountDebitBalance = $accountTotalAmount < 0 ?  $accountTotalAmount * -1: 0 ;
        } else {
            $accountTotalAmount = $debitAmount - $creditAmount;


            $accountCreditBalance = $accountTotalAmount < 0 ? $accountTotalAmount * -1 : 0;
            $accountDebitBalance = $accountTotalAmount > 0 ? $accountTotalAmount : 0 ;
        }

        return [
            'credit_amount' => $debitAmount,
            'debit_amount' => $creditAmount,
            'credit_balance' => $accountCreditBalance,
            'debit_balance' => $accountDebitBalance,
            'total_amount' => $accountTotalAmount,
        ];
    }

    public function _getDebitTransactionsAmount($whereStatements = [])
    {

        if ($whereStatements == [])
            $whereStatements = $this->_getAdditionalWhereStatement();


//        $amount =
        return  $this->debit_transaction()->where($whereStatements)->sum('amount');
//        if($this->statistics == null)
//        {
//            $this->statistics()->create([
//                'debit_amount' => $amount
//            ]);
//        }
//        return $amount;
    }

    private function _getAdditionalWhereStatement()
    {
        $where = [];
        if ($this->_isClients())
            $where = [];//['description','client_balance']

        if ($this->_isVendors())
            $where = [];//['description','vendor_balance']


        if ($where == [])
            $where = [['id', '>', 0]];

        return $where;
    }

    public function _isClients()
    {
        return $this->slug == 'clients';
    }

    public function _isVendors()
    {
        return $this->slug == 'vendors';
    }

    public function _getCreditTransactionsAmount($whereStatements = [])
    {
        if ($whereStatements == [])
            $whereStatements = $this->_getAdditionalWhereStatement();

        return $this->credit_transaction()->where($whereStatements)->sum('amount');
    }

    public function _isCredit()
    {
        return $this->type == 'credit';
    }

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
        $nestedTreeIds = $this->returnNestedTreeIds($this);
        return $this->_getAccountsTreeBalance($nestedTreeIds);
    }

    private function _getAccountsTreeBalance($treeIds = [])
    {
        $debitAmount = AccountStatistic::whereIn("account_id", $treeIds)->sum('debit_amount');
        $creditAmount = AccountStatistic::whereIn("account_id", $treeIds)->sum('credit_amount');

        // die($treeIds);
        // exit();

        // return   $debitAmount;
        if($this->_isCredit()) { return (float)$creditAmount - (float)$debitAmount;}
        else {return (float)$debitAmount - (float)$creditAmount; }
    }

    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar')) return $this->ar_name;
        else return $this->name;
    }

    public function _getStatisticsInstance()
    {
        if ($this->statistics == null) {
            $whereStatements = $this->_getAdditionalWhereStatement();
            $creditAmount = $this->_getCreditTransactionsAmount($whereStatements);
            $debitAmount = $this->_getDebitTransactionsAmount($whereStatements);
            // $total = $this->_getCurrentBalanceUsingTransaction();
            $static = $this->statistics()->create([
                'credit_amount' =>  $creditAmount,
                'debit_amount' => $debitAmount ,
                'transactions_count' => 0
            ]);
        }else{
            $static = $this->statistics->fresh();
        }


        return   $static;
    }

    public function _getCurrentBalanceUsingTransaction()
    {
        if ($this->_isStock())
            $balance = $this->_getStockBalanceUsingTransactions();
        else
            $balance = $this->_getAccountBalanceUsingTransactions();

        return $balance;
    }

    public function _isStock()
    {
        return $this->slug == 'stock';
    }

    private function _getStockBalanceUsingTransactions()
    {
        $creditAmount =  $this->moneyFormatter($this->_getStockCreditAmount());
        $debitAmount =  $this->moneyFormatter($this->_getStockDebitAmount());
        $this->_updateCreditAndDebitAmountForAccount($creditAmount, $debitAmount);
        return $debitAmount - $creditAmount;
    }

    public function _getStockCreditAmount()
    {
        return Transaction::where('creditable_type', 'App\Item')->sum('amount');
    }

    public function _getStockDebitAmount()
    {
        return Transaction::where('debitable_type', 'App\Item')->sum('amount');
    }

    public function _updateCreditAndDebitAmountForAccount($creditAmount, $debitAmount)
    {

        if ($this->statistics == null) {
            $credit = $this->statistics()->create([
                'credit_amount' => (float)$creditAmount,
                'debit_amount' => (float)($debitAmount)
            ]);
        } else {
            $credit = $this->statistics()->update([
                'credit_amount' => (float)($creditAmount),
                'debit_amount' => (float)($debitAmount)
            ]);
        }
        

        return $credit;

    }

    public function _getAccountBalanceUsingTransactions($creditAmount = null, $debitAmount = null)
    {
        if ($creditAmount === null && $debitAmount === null) {
            $whereStatements = $this->_getAdditionalWhereStatement();
            $creditAmount = $this->moneyFormatter($this->_getCreditTransactionsAmount($whereStatements));
            $debitAmount =  $this->moneyFormatter($this->_getDebitTransactionsAmount($whereStatements));
        }

        // update credit and debit amount
        $this->_updateCreditAndDebitAmountForAccount($creditAmount, $debitAmount);

        if ($this->_isCredit()) return (float)$creditAmount - (float)$debitAmount;
        return (float)$debitAmount - (float)$creditAmount;
    }

    public function _updateBalanceUsingTransactions()
    {
        $balance = $this->_getCurrentBalanceUsingTransaction();
        $this->statistics()->update([
            'total_amount' => $balance
        ]);
        return $balance;
    }

    public function _isDebit()
    {
        return $this->type == 'debit';
    }

}