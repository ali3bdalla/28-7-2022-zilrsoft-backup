<?php
	
	namespace App;
	
	use App\Attributes\PaymentAttributes;
	use App\Relationships\PaymentRelationships;
	
	class Payment extends BaseModel
	{
		
		protected $guarded = [];
		
		use PaymentRelationships,PaymentAttributes;
		

		public function paymentable()
		{
			return $this->morphTo();
		}
	}
