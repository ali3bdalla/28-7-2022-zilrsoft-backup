<?php
	
	namespace App\Relationships;
	
	
	use App\Account;
	use App\CountryBank;
	use App\Gateway;
	use App\GatewayAccounts;
	use App\Invoice;
	use App\InvoicePayments;
	use App\Manager;
	use App\User;
	
	/**
	 * Trait PaymentRelationships
	 *
	 * @package App\Relationships
	 */
	trait PaymentRelationships
	{
		
		
		
		
		public function payment_invoices()
		{
			return $this->hasMany(InvoicePayments::class,'payment_id');
		}
		
		public function user()
		{
			return $this->belongsTo(User::class,'user_id');
		}
		
		
		public function invoice()
		{
			return $this->belongsTo(Invoice::class,'invoice_id');
		}
		
		public function creator()
		{
			return $this->belongsTo(Manager::class,'creator_id');
		}
		
		public function gateway()
		{
			return $this->belongsTo(Gateway::class,'gateway_id');
		}
		
		
		public function bank()
		{
			return $this->belongsTo(CountryBank::class,'bank_id');
		}
		
		
		
		public function organization_account()
		{
			return $this->belongsTo(GatewayAccounts::class,'organization_account_id');
		}
		
		public function account()
		{
			return $this->belongsTo(Account::class,'chart_id');
		}

		public function user_account()
		{
			return $this->belongsTo(GatewayAccounts::class,'user_account_id');
		}
		
//		public function user_method()
//		{
//			return $this->belongsTo(Gateway::class,'user_method_id');
//		}
		
	}
