<?php
	
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	
	class InvoiceExpenses extends Model
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
