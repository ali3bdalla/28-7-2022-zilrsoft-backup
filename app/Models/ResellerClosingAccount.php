<?php

	namespace App\Models;


use App\Models\Traits\AccountingPeriodTrait;
use Illuminate\Http\Request;

    /**
	 * @property mixed receiver_id
     * @property mixed remaining_accounts_balance
     * @property mixed container
     * @property mixed transaction_type
     * @property mixed creator_id
     */
	class ResellerClosingAccount extends BaseModel
	{
		use AccountingPeriodTrait;

		protected $guarded = [];
		/**
		 * @var mixed
		 */


		public function creator()
		{
			return $this->belongsTo(Manager::class,'creator_id')->withTrashed();
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
			return $query->withoutGlobalScopes(['draft','pending'])->where([['is_pending', true], ['transaction_type', 'transfer'], ['receiver_id', $request->user()->id]])->with('creator', 'receiver','fromAccount','toAccount');
		}
	}
