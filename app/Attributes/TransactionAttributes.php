<?php


namespace App\Attributes;


use App\Account;
use App\Item;

trait TransactionAttributes
{
    public function _isCreditAbleItem()
    {
        return $this->creditable instanceof Item;
    }
    public function _isDebitAbleItem()
    {
        return $this->debitable instanceof Item;
    }
    public function _isCreditAbleAccount()
    {
        return $this->creditable instanceof Account && $this->creditable != Account::getUsingSlug('stock');
    }
    public function _isDebitAbleAccount()
    {
        return $this->debitable instanceof Account && $this->debitable !=  Account::getUsingSlug('stock');
    }
    public function _getDebitAbleAccount()
    {
        return $this->debitable;
    }
    public function _getCreditAbleAccount()
    {
        return $this->creditable;
    }

    
    
}