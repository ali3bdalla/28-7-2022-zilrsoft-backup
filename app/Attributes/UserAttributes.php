<?php
	
	namespace App\Attributes;
	
	use App\Models\Account;
    use Carbon\Carbon;
	trait UserAttributes
	{


        public function isSystemUser()
        {
            return $this->is_system_user;
	    }
        public function _getClientBalance()
        {
            return $this->balance ;
	    }



        public function _getVendorBalance()
        {
            return $this->getOriginal('vendor_balance') ;
        }


        public function _isClient()
        {
            return $this->is_client == true;
	    }

        public function _isVendor()
        {
            return $this->is_vendor == true;
        }


        public function _isManager()
        {
            return $this->is_manager == true;
        }

        public function _getUserBalanceUsingTransactions(Account $account,$balanceType = 'client')
        {
            if($balanceType == 'client')
            {
                $balance = $this->_getClientBalanceUsingTransactions($account);
            }
            else
            {
                $balance = $this->_getVendorBalanceUsingTransactions($account);

            }
            return $balance;
	    }
        public function _updateAndGetUserBalanceUsingTransaction(Account $account,$balanceType = 'client')
        {
            $balance = $this->_getUserBalanceUsingTransactions($account,$balanceType);

            if($balanceType == 'client')
            {
                $this->update([
                    'balance' => $balance
                ]);
            }
            else
            {
                $this->update([
                    'vendor_balance' => $balance
                ]);
            }
            return $balance;
	    }


        public function _getClientBalanceUsingTransactions(Account $account)
        {
            return $account->debit_transaction()->where(
                    [
                        ['user_id',$this->id],
                        ['description','client_balance']
                    ]
                )->sum('amount') -
                $account->credit_transaction()->where(
                    [
                        ['user_id',$this->id],
                        ['description','client_balance']
                    ]
                )->sum('amount');
	    }



        public function _getVendorBalanceUsingTransactions(Account $account)
        {
            return $account->credit_transaction()->where(
                [
                    ['user_id',$this->id],
                    ['description','vendor_balance']
                ])->sum('amount') - $account->debit_transaction()->where(
                    [
                        ['user_id',$this->id],
                        ['description','vendor_balance']
                    ]
                )->sum('amount');
        }




		
		public function getCreatedAtAttribute($value)
		{
			return Carbon::parse($value)->toDateString();
		}
		
		public function getLocaleNameAttribute()
		{
			if (app()->isLocale('ar'))
				return $this->name_ar;
			
			
			return $this->name;
        }
	}
