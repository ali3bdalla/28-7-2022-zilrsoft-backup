<?php
	
	namespace App\Relationships;
	
	
	use App\Models\Invoice;
	use App\Models\ItemSerials;
	use App\Models\Manager;
	use App\Models\User;
	
	trait PurchaseInvoiceRelationships
	{
		
		public function invoice()
		{
			return $this->belongsTo(Invoice::class,'invoice_id');
		}
		
		public function vendor()
		{
			return $this->belongsTo(User::class,'vendor_id');
		}
		
		public function serials()
		{
			return $this->hasMany(ItemSerials::class,'purchase_invoice_id','invoice_id');
		}
		
		public function receiver()
		{
			return $this->belongsTo(Manager::class,'receiver_id');
		}
		
	}
