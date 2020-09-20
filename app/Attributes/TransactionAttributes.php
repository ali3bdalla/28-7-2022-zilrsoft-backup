<?php


namespace App\Attributes;


use App\Models\Account;
use App\Models\Item;
use App\Models\User;

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

    
    

    public function getAccountNameAttribute()
    {
        $account = $this->account;
        if(($account->slug == 'vendors' || $account->slug == 'clients' )&& $this->user_id)
        {
            $user = User::find($this->user_id);
            if($user)
            {
                return $user->name;
            }
            
        }


        if(($account->slug == 'stock' )&& $this->item_id)
        {
            $item = Item::find($this->item_id);
            if($item)
            {
                return $item->locale_name;
            }
            
        }


        return $this->account->locale_name;
    }
}