<?php
	
	namespace App\Attributes;
	
	use App\Account;
    use Carbon\Carbon;
	trait UserAttributes
	{

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
		
//		public function scopeVendors($query)
//		{
//			return $query->where([
//				['is_vendor',true],
//				['is_system_user',false]
//			]);
//		}
		
//		public function scopeClients($query)
//		{
//			return $query->where([
//				['is_client',true],
//				['is_system_user',false],
//			]);
//		}
		
//		public function scopeManagers($query)
//		{
//			return $query->where('is_manager',true)->with('manager');
//		}
//
//		public function updateUserBalance($option,$value)
//		{
//			if ($option == 'add' && is_numeric($value)){
//				$this->update([
//					'balance' => DB::raw("balance + $value")
//				]);
//			}else
//				if ($option == 'sub' && is_numeric($value)){
//					$this->update([
//						'balance' => DB::raw("balance - $value")
//					]);
//				}
//		}
//
	}
