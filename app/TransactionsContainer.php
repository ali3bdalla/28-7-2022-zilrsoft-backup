<?php
	
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	
	class TransactionsContainer extends Model
	{
		protected $guarded = [];
		
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
