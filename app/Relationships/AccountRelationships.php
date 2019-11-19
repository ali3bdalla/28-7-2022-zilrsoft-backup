<?php
	
	
	namespace App\Relationships;
	
	
	use App\Account;
	use App\Transaction;
	
	trait AccountRelationships
	{
		public function parent()
		{
			return $this->belongsTo($this,'parent_id');
		}
		
		public function children()
		{
			return $this->hasMany($this,'parent_id')->with(
				'children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children'
			);
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