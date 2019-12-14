<?php
	
	
	namespace App\Relationships;
	
	use App\Branch;
	use App\Department;
	use App\Invoice;
	use App\InvoiceExpenses;
	use App\InvoiceItems;
	use App\InvoicePayments;
	use App\Manager;
	use App\Organization;
	use App\Payment;
	use App\PurchaseInvoice;
	use App\SaleInvoice;
	use App\SerialHistory;
	use App\Transaction;
	use App\User;
	
	trait InvoiceRelationship
	{
		
		public function user()
		{
			return User::find($this->user_id);
			
		}
		
		public function expenses()
		{
			return $this->hasMany(InvoiceExpenses::class,'invoice_id');
		}
		
		public function chart()
		{
			return $this->belongsTo(Chart::class,'chart_id');
		}
		
		public function organization()
		{
			return $this->belongsTo(Organization::class,'organization_id');
		}
		
		public function sale()
		{
			return $this->hasOne(SaleInvoice::class,'invoice_id');
		}
		
		public function creator()
		{
			return $this->belongsTo(Manager::class,'creator_id');
		}
		
		public function department()
		{
			return $this->belongsTo(Department::class,'department_id');
		}
		
		public function branch()
		{
			return $this->belongsTo(Branch::class,'branch_id');
		}
		
		public function items()
		{
			return $this->hasMany(InvoiceItems::class,'invoice_id');
		}
		
		public function purchase()
		{
			return $this->hasOne(PurchaseInvoice::class,'invoice_id');
		}
		
		public function serial_history()
		{
			return $this->hasOne(SerialHistory::class,'invoice_id');
		}
		
		public function child()
		{
			return $this->belongsTo(Invoice::class,'parent_invoice_id');
		}
		
		public function transactions()
		{
			return $this->hasMany(Transaction::class,'invoice_id');
		}
		
		public function payments()
		{
			return $this->hasMany(Payment::class,'invoice_id');
		}
		
		public function invoice_payments()
		{
			return $this->hasMany(InvoicePayments::class,'invoice_id');
		}
		
	}
