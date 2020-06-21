<?php
	
	namespace App;
	
	use App\DatabaseHelpers\PurchaseInvoiceHelper;
	use App\Relationships\PurchaseInvoiceRelationships;

	
	class PurchaseInvoice extends BaseModel
	{
		
		use PurchaseInvoiceRelationships,PurchaseInvoiceHelper;
		
		protected $guarded = [];
		

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
