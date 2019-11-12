<?php
	
	namespace App;
	
	use App\DatabaseHelpers\PurchaseInvoiceHelper;
	use App\Relationships\PurchaseInvoiceRelationships;
	use App\Scopes\OrganizationScope;
	use Illuminate\Database\Eloquent\Model;
	
	class PurchaseInvoice extends Model
	{
		
		
		use PurchaseInvoiceRelationships,PurchaseInvoiceHelper;
		
		protected $guarded = [];
		
		
		protected static function boot()
		{
			parent::boot();
			if (auth()->check()){
				static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
			}
		}
		
		public function addSerialsToItemOfSubInvoice($data,$invoice_type = 'purchase',$user_id = 0)
		{
			foreach ($data['serials'] as $serial){
				
				
				$new_serial = $this->serials()->create([
					'organization_id' => auth()->user()->organization_id,
					'creator_id' => auth()->user()->id,
					'purchase_invoice_id' => $this->invoice->id,
					'item_id' => $data['id'],
					'serial' => $serial,
					'current_status' => 'available'
				]);
				
				
				$this->invoice->serial_history()->create([
					'event' => $invoice_type,
					'organization_id' => auth()->user()->organization_id,
					'creator_id' => auth()->user()->id,
					'serial_id' => $new_serial->id,
					'user_id' => $user_id
				]);

//
//            print_r($new_serial);
//            exit();
			
			}
		}
		
	}
