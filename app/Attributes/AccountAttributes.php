<?php
	

namespace App\Attributes;

use App\Account;
use App\AccountStatistic;
use App\Transaction;


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
        return money_format("%i",$this->_getAccountsTreeBalance($nestedTreeIds));
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
            return  $this->statistics()->create([
                'total_amount' => $this->_getCurrentBalanceUsingTransaction(),
                'transactions_count' => 0
            ]);
        }
        return $this->statistics;
    }

    private function _getAccountsTreeBalance($treeIds = [])
    {
        return AccountStatistic::whereIn("account_id",$treeIds)->sum('total_amount');
    }
    private function _getAccountBalanceUsingTransactions()
    {
        $whereStatements = $this->_getAdditionalWhereStatement();
        if ($this->_isCredit()) return $this->credit_transaction()->where($whereStatements)->sum('amount') - $this->debit_transaction()->where($whereStatements)->sum('amount');
        return $this->debit_transaction()->where($whereStatements)->sum('amount') -  $this->credit_transaction()->where($whereStatements)->sum('amount');
    }

    private function _getAdditionalWhereStatement()
    {
        $where = [];
        if($this->_isClients())
            $where =  [['description','client_balance']];

        if($this->_isVendors())
            $where =  [['description','vendor_balance']];


        if($where == [])
            $where = [['id','>',0]];

        return  $where;
    }
    private function _getStockBalanceUsingTransactions()
    {
        return Transaction::where('debitable_type', 'App\Item')->sum('amount') - Transaction::where('creditable_type', 'App\Item')->sum('amount');
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