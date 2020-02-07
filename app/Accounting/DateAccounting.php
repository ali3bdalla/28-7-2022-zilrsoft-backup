<?php
	
	
	namespace App\Accounting;
	
	
	use Illuminate\Support\Carbon;
	
	trait DateAccounting
	{
		public function toGetLastCloseAmountDate()
		{
			$lastAccountCloseTransaction = auth()->user()->private_transactoins()->where([
				['creator_id',auth()->user()->id],
				['transaction_type',"close_account"],
			])->orderBy('id','desc')->first();
			
			return empty($lastAccountCloseTransaction) ? Carbon::today()->subYears(2) :
				$lastAccountCloseTransaction->created_at;
			
		}
	}