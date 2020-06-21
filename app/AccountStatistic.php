<?php
	
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	
	class AccountStatistic extends BaseModel
	{
		protected $guarded = [];
		
		public function account()
		{
			return $this->belongsTo(Account::class,'account_id');
		}
		
		//
	}
