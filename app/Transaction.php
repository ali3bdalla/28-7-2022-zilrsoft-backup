<?php
	
	namespace App;
	
	use App\DatabaseHelpers\TransactionHelper;
	use Illuminate\Database\Eloquent\Model;
	
	class Transaction extends Model
	{
		use TransactionHelper;
		
		protected $guarded = [];
		
		protected $casts = [
			'debitable_type' => 'string',
			'debitable_id' => 'integer',
			'creditable_type' => 'string',
			'creditable_id' => 'integer',
		];
		
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
