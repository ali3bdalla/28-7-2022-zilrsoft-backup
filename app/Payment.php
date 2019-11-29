<?php
	
	namespace App;
	
	use App\Attributes\PaymentAttributes;
	
	use App\DatabaseHelpers\ChartPaymentHelper;
	use App\Relationships\PaymentRelationships;
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	
	class Payment extends Model
	{
		
		protected $guarded = [];
		
		use PaymentRelationships,PaymentAttributes,ChartPaymentHelper;
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
		}
		
//		public function invoices()
//		{
//
//		}
//
		public function paymentable()
		{
			return $this->morphTo();
		}
	}
