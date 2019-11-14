<?php
	
	namespace App\Relationships;
	
	use App\GatewayAccounts;
	use App\Invoice;
	use App\InvoiceItems;
	use App\InvoicePayments;
	use App\Manager;
	use App\Organization;
	use App\SaleInvoice;
	use App\UserDetails;
	use Illuminate\Database\Eloquent\Builder;
	
	trait UserRelationships
	{
		
		public function details()
		{
			return $this->hasOne(UserDetails::class,'user_id');
		}
		
		public function organization()
		{
			return $this->belongsTo(Organization::class,'organizaiton_id');
		}
		
		public function client_invoices()
		{
			return $this->hasMany(SaleInvoice::class,'client_id');
		}
		
		
		public function client_payments_invoice($invoice_ids = [])
		{
			return InvoicePayments::whereIn('invoice_id',$invoice_ids)->with('invoice')->get();
		}
		
		public function creator()
		{
			return $this->belongsTo(Manager::class,'creator_id');
		}
		
		public function manager()
		{
			return $this->hasOne(Manager::class,'user_id');
		}


//
//    public function billings()
//    {
//        return $this->morphMany('App\Payment', 'billingable');
//    }
		
		
		public function accounts()
		{
			return $this->morphMany(GatewayAccounts::class,'accountable');
		}
//
//	public function banks()
//	{
//		return $this->hasManyThrough(CountryBank::class,GatewayAccounts::class,'bank_id','')
//    }
	
	}
