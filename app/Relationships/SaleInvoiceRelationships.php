<?php
	
	namespace App\Relationships;
	
	use App\Models\Invoice;
	use App\Models\ItemSerials;
	use App\Models\Manager;
	use App\Models\User;
	use App\Models\InvoiceItems;
	
	trait SaleInvoiceRelationships
	{
		
		public function invoice()
		{
			return $this->belongsTo(Invoice::class,'invoice_id');
		}
		
		public function client()
		{
			return $this->belongsTo(User::class,'client_id');
		}
		
		public function salesman()
		{
			return $this->belongsTo(Manager::class,'salesman_id');
		}
		
		public function serials()
		{
			return $this->hasMany(ItemSerials::class,'sale_invoice_id','invoice_id');
		}
		
	}
