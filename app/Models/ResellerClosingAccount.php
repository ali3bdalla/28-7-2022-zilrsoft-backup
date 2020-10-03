<?php
	
	namespace App\Models;
	

	
	class ResellerClosingAccount extends BaseModel
	{
		
		protected $guarded = [];

		public function creator()
		{
			return $this->belongsTo(Manager::class,'creator_id');
		}
		
		public function receiver()
		{
			return $this->belongsTo(Manager::class,'receiver_id');
		}

		public function container()
		{
			return $this->belongsTo(TransactionsContainer::class,'container_id')->withoutGlobalScope("pendingTransactionsContainerScope");
		}

	}
