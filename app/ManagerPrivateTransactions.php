<?php
	
	namespace App;
	
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Support\Facades\Auth;
	
	class ManagerPrivateTransactions extends Model
	{
		
		protected $guarded = [];
		
		protected static function boot()
		{
			parent::boot();
			if (Auth::check()){
				static::addGlobalScope(new OrganizationScope(Auth::user()->organization_id));
				
			}
		}
		
		public function creator()
		{
			return $this->belongsTo(Manager::class,'creator_id');
		}
		
		public function receiver()
		{
			return $this->belongsTo(Manager::class,'receiver_id');
		}
//
		public function container()
		{
			return $this->belongsTo(TransactionsContainer::class,'transaction_container_id')->withoutGlobalScope("pendingTransactionsContainerScope");
		}
//
//		public function receiver()
//		{
//			return $this->belongsTo(Manager::class,'receiver_id');
//		}
		//
	}
