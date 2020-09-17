<?php
	
	namespace App\Models;
	
	
	class InvoiceExpenses extends BaseModel
	{
		
		protected $guarded = [];
		
		public function invoice()
		{
			return $this->belongsTo(Invoice::class,'invoice_id');
		}
		
		public function expense()
		{
			return $this->belongsTo(Expense::class,'expense_id');
		}
		
		//
	}
