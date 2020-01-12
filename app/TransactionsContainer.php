<?php
	
	namespace App;
	
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Model;
	
	class TransactionsContainer extends Model
	{
		protected $guarded = [];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope('pendingTransactionsContainerScope',function (Builder $builder){
					$builder->where('is_pending',false);
				});
			}
			
		}
		
		
		public function transactions()
		{
			return $this->hasMany(Transaction::class,'container_id');
		}
		
		public function invoice()
		{
			return $this->belongsTo(Invoice::class,'invoice_id');
		}
//		public function update_amount()
//		{
//			$this->
//		}
//		//
	}
