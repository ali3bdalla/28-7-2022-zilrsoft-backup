<?php
	
	namespace App;
	
//	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	
	class InvoicePayments extends Model
	{
		protected $guarded = [];
		
//		protected static function boot()
//		{
//			parent::boot();
//			if (auth()->check()){
//				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
//			}
//		}
		
		public function invoice()
		{
			return $this->belongsTo(Invoice::class,'invoice_id');
		}
		
		public function payment()
		{
			
			return $this->belongsTo(Payment::class,'payment_id');
		}
		
	}
