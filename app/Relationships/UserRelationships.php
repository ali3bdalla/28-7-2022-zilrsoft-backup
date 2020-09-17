<?php
	
	namespace App\Relationships;
	
	use App\Models\Account;
	use App\Models\InvoicePayments;
	use App\Models\Manager;
	use App\Models\Organization;
	use App\Models\SaleInvoice;
	use App\Models\Transaction;
	use App\Models\UserDetails;
	use App\Models\UserGateways;
	
	trait UserRelationships
	{
		
		/**
		 * @param Account $account
		 *
		 * @return mixed
		 */
		public function vendorTransactionsAmount(Account $account)
		{
			return $account->credit_transaction()->where('user_id',$this->id)->sum('amount') -
				$account->debit_transaction
				()->where('user_id',$this->id)->sum('amount');
		}
		
		/**
		 * @param Account $account
		 *
		 * @return mixed
		 */
		public function cleintTransactionsAmount(Account $account)
		{
			return $account->debit_transaction()->where('user_id',$this->id)->sum('amount') -
				$account->credit_transaction()->where('user_id',$this->id)->sum('amount');
		}
		
		public function credit_transaction()
		{
			return $this->morphMany(Transaction::class,'creditable');
		}
		
		public function debit_transaction()
		{
			return $this->morphMany(Transaction::class,'debitable');
		}
		
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
		
	}
