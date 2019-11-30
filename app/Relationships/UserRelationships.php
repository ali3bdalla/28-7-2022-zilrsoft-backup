<?php
	
	namespace App\Relationships;
	
	use App\InvoicePayments;
	use App\Manager;
	use App\Organization;
	use App\SaleInvoice;
	use App\Transaction;
	use App\UserDetails;
	use App\UserGateways;
	
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
		
		public function gateways()
		{
			return $this->hasMany(UserGateways::class,'user_id');
		}
		
		public function credit_transaction()
		{
			return $this->morphMany(Transaction::class,'creditable');
		}
		
		public function debit_transaction()
		{
			return $this->morphMany(Transaction::class,'debitable');
		}
		
	}
