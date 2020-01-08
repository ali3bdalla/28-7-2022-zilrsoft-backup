<?php
	
	namespace App\Attributes;
	
	
	trait PaymentAttributes
	{
		
		public function scopeData()
		{
			return $this->with(
				'user',
				'main_method',
				'user_account',
				'organization_account',
				'organization_method',
				'user_method',
				'payment_invoices.invoice');
		}
		
		public function getSteakholderNameAttribute()
		{
			return $this->user->locale_name;
		}
		
		public function getSteakholderTypeAttribute()
		{
			if (in_array($this->payment_type,['receipt'])){
				return __('pages/vouchers.client');
			}
			
			return __('pages/vouchers.vendor');
		}
		
		public function getSteakholderPhoneNumberAttribute()
		{
			return $this->user->phone_number;
		}
		
		public function getAmountAttribute($value)
		{
			return money_format("%i",$value);
		}
		
	}
