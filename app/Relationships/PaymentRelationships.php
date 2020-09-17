<?php
	
	namespace App\Relationships;
	
	
	use App\Models\CountryBank;
	use App\Gateway;
	use App\GatewayAccounts;
	use App\Models\Invoice;
	use App\Models\Manager;
	use App\Models\User;
	use App\Models\UserGateways;
	
	/**
	 * Trait PaymentRelationships
	 *
	 * @package App\Relationships
	 */
	trait PaymentRelationships
	{

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
		
		public function user_gateway()
		{
			return $this->belongsTo(UserGateways::class,'user_account_id');
		}
		
	}
