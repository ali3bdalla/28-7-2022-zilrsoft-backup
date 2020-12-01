<?php
	
	namespace App\Models;
	

	
	use Illuminate\Http\Request;
	
	/**
	 * @property mixed receiver_id
	 */
	class ResellerClosingAccount extends BaseModel
	{
		
		protected $guarded = [];
		/**
		 * @var mixed
		 */
	
		
		public function creator()
		{
			return $this->belongsTo(Manager::class,'creator_id');
		}
		
		public function receiver()
		{
			return $this->belongsTo(Manager::class,'receiver_id');
		}
		
		
		public function receivedGateway()
		{
			return $this->belongsTo(Account::class,'receiver_id');
		}
		
		
		public function fromAccount()
		{
			return $this->belongsTo(Account::class,'from_account_id');
		}
		
		public function toAccount()
		{
			return $this->belongsTo(Account::class,'to_account_id');
		}
		
		public function container()
		{
			return $this->belongsTo(TransactionsContainer::class,'container_id')->withoutGlobalScope("pending");
		}
		
		
		public function scopeToMe($query,Request $request)
		{
			return $query->where([['is_pending', true], ['transaction_type', 'transfer'], ['receiver_id', $request->user()->id]])->with('creator', 'receiver','fromAccount','toAccount')->withoutGlobalScopes(['draft','pending']);
		}
	}
