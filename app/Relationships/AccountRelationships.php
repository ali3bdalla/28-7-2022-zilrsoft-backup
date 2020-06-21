<?php
	
	
	namespace App\Relationships;
	
	
	use App\AccountStatistic;
	use App\Transaction;
	
	trait AccountRelationships
	{
		
		 public function statistics()
		 {
		 	return $this->hasOne(AccountStatistic::class,'account_id');
		 }
		

		public function parent()
		{
			return $this->belongsTo($this,'parent_id');
		}
		
		public function children()
		{
			return $this->hasMany($this,'parent_id');
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