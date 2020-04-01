<?php
	
	
	namespace App\Relationships;
	
	
	use App\AccountStatistic;
	use App\Payment;
	use App\Transaction;
	
	trait AccountRelationships
	{
		
		public function statistics()
		{
			return $this->hasOne(AccountStatistic::class,'account_id');
		}
		
		public function paymentable()
		{
			return $this->morphMany(Payment::class,'paymentable');
		}
		
		public function parent()
		{
			return $this->belongsTo($this,'parent_id');
		}
		
		public function children()
		{
			return $this->hasMany($this,'parent_id');
		}
		
		/*
		 *
		 * transactions
		 * */
		public function credit_transaction()
		{
			return $this->morphMany(Transaction::class,'creditable');
		}
		
		public function debit_transaction()
		{
			return $this->morphMany(Transaction::class,'debitable');
		}
	}