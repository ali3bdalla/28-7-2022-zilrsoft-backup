<?php
	
	namespace App\Models;
	
	use App\Attributes\TransactionAttributes;
use App\Traits\OrmNumbersTrait;
use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class Transaction extends BaseModel
	{
		use TransactionAttributes,SoftDeletes,OrmNumbersTrait;
		
		protected $guarded = [];
		
		protected $casts = [
			'debitable_type' => 'string',
			'debitable_id' => 'integer',
			'creditable_type' => 'string',
			'creditable_id' => 'integer',
            'amount' => 'float'
		];
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope('pendingTransactionScope',function (Builder $builder){
					$builder->where('is_pending',false);
				});
			}
			
		}
		
		
		
		public function creditable()
		{
			return $this->morphTo();
		}
		
		public function debitable()
		{
			return $this->morphTo();
		}
		
		public function user()
		{
			return $this->belongsTo(User::class,'user_id');
		}
		
		public function invoice()
		{
			return $this->belongsTo(Invoice::class,'invoice_id');
		}
		
		public function container()
		{
			return $this->belongsTo(TransactionsContainer::class,'container_id');
		}
		
	}
