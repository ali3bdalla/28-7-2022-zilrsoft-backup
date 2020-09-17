<?php
	
	namespace App\Models;

	class SerialHistory extends BaseModel
	{
		protected $guarded = [];
		

		
		public function serial()
		{
			return $this->belongsTo(ItemSerials::class,'serial_id');
		}
		
		public function creator()
		{
			return $this->belongsTo(Manager::class,'creator_id');
		}
		
		public function user()
		{
			return $this->belongsTo(User::class,'user_id');
		}
		
		public function invoice()
		{
			return $this->belongsTo(Invoice::class,'invoice_id');
		}
		//
	}
