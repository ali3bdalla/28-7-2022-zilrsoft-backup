<?php
	
	namespace App;
	

	class InvoicePayments extends BaseModel
	{
		protected $guarded = [];

		
		public function invoice()
		{
			return $this->belongsTo(Invoice::class,'invoice_id');
		}
		
		public function payment()
		{
			
			return $this->belongsTo(Payment::class,'payment_id');
		}
		
	}
