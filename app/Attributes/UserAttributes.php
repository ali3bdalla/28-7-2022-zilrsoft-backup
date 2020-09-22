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

      
		public function getCreatedAtAttribute($value)
		{
			return Carbon::parse($value)->toDateString();
		}
		
		public function getLocaleNameAttribute()
		{
			// if (app()->isLocale('ar'))
			// 	return $this->ar_name;
			
			
			return $this->name;
        }
	}
