<?php
	

namespace App\Attributes;

use App\Models\Account;
use App\Models\AccountStatistic;
use App\Models\Transaction;


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
        $nestedTreeIds = $this->returnNestedTreeIds($this);
        return $this->_getAccountsTreeBalance($nestedTreeIds);
    }


    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar')) return $this->ar_name;
        else return $this->name;
    }


	public static function toGetAccountWithSlug($slug,$is_system_account = true)
	{
		return Account::where([['slug',$slug],['is_system_account',$is_system_account]])->first();
	}
	



    public static function getUsingSlug($slug,$is_system_account = true)
    {
        return (new static())->toGetAccountWithSlug($slug,$is_system_account);
	}



    public function _getStatisticsInstance()
    {
        if($this->statistics == null)
        {
            $total = $this->_getCurrentBalanceUsingTransaction();
            $this->statistics()->update([
                'total_amount' => $total,
                'transactions_count' => 0
                ]);
        }


        return $this->statistics->fresh();
    }

    private function _getAccountsTreeBalance($treeIds = [])
    {
        return AccountStatistic::whereIn("account_id",$treeIds)->sum('total_amount');
    }
    public function _getAccountBalanceUsingTransactions($creditAmount = null , $debitAmount = null)
    {
        if($creditAmount === null && $debitAmount === null)
        {
            $whereStatements = $this->_getAdditionalWhereStatement();
            $creditAmount = $this->_getCreditTransactionsAmount($whereStatements);
            $debitAmount = $this->_getDebitTransactionsAmount($whereStatements);
        }

        // update credit and debit amount
        $this->_updateCreditAndDebitAmountForAccount($creditAmount,$debitAmount);

        if ($this->_isCredit()) return  $creditAmount - $debitAmount ;
        return $debitAmount - $creditAmount;
    }


    public function _updateCreditAndDebitAmountForAccount($creditAmount,$debitAmount)
    {

        if($this->statistics != null)
        {
            $this->statistics()->update([
                'credit_amount' => floatval($creditAmount) ,
                'debit_amount' => floatval($debitAmount)
            ]);
        }else {
            $this->statistics()->update([
                'credit_amount' => floatval($creditAmount) ,
                'debit_amount' => floatval($debitAmount)
            ]);
        }

    }


    public function _getCreditTransactionsAmount($whereStatements = [])
    {
        if( $whereStatements == [])
            $whereStatements =  $this->_getAdditionalWhereStatement();
       return  $this->credit_transaction()->where($whereStatements)->sum('amount');
    }


    public function _getDebitTransactionsAmount($whereStatements = [])
    {

        if( $whereStatements == [])
            $whereStatements =  $this->_getAdditionalWhereStatement();

       return  $this->debit_transaction()->where($whereStatements)->sum('amount');
    }


    private function _getAdditionalWhereStatement()
    {
        $where = [];
        if($this->_isClients())
            $where =  [];//['description','client_balance']

        if($this->_isVendors())
            $where =  [];//['description','vendor_balance']


        if($where == [])
            $where = [['id','>',0]];

        return  $where;
    }
    private function _getStockBalanceUsingTransactions()
    {

        $creditAmount =  $this->_getStockCreditAmount();
        $debitAmount = $this->_getStockDebitAmount();
        $this->_updateCreditAndDebitAmountForAccount($creditAmount,$debitAmount);

        return  $debitAmount -  $creditAmount;
    }

    public function _getStockCreditAmount()
    {
        return Transaction::where('creditable_type', 'App\Models\Item')->sum('amount');
    }

    public function _getStockDebitAmount()
    {
       return Transaction::where('debitable_type', 'App\Models\Item')->sum('amount');
    }

    public function _getCurrentBalanceUsingTransaction()
    {
        if($this->_isStock())
            $balance =  $this->_getStockBalanceUsingTransactions();
        else
            $balance =  $this->_getAccountBalanceUsingTransactions();

        return $balance;
    }

    public function _updateBalanceUsingTransactions()
    {
        $balance = $this->_getCurrentBalanceUsingTransaction();
        $this->statistics()->update([
           'total_amount' => $balance
        ]);
        return $balance;
    }



    public function _isStock()
    {
        return $this->slug == 'stock';
	}
    public function _isClients()
    {
        return $this->slug == 'clients';
    }

    public function _isVendors()
    {
        return $this->slug == 'vendors';
    }
    public function _isCredit()
    {
        return $this->type == 'credit';
	}
    public function _isDebit()
    {
        return $this->type == 'debit';
    }

}