<?php
	
	namespace App\Models;
	
	use App\Relationships\PurchaseRelationships;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends BaseModel
	{
		
		use PurchaseRelationships;
		use SoftDeletes;

		protected $guarded = [];
		

		// public function addSerialsToItemOfSubInvoice($data,$invoice_type = 'purchase',$user_id = 0)
		// {
		// 	foreach ($data['serials'] as $serial){
				
		// 		$new_serial = $this->serials()->create([
		// 			'organization_id' => auth()->user()->organization_id,
		// 			'creator_id' => auth()->user()->id,
		// 			'purchase_invoice_id' => $this->invoice->id,
		// 			'item_id' => $data['id'],
		// 			'serial' => $serial,
		// 			'current_status' => 'available'
		// 		]);
				
		// 		$this->invoice->serial_history()->create([
		// 			'event' => $invoice_type,
		// 			'organization_id' => auth()->user()->organization_id,
		// 			'creator_id' => auth()->user()->id,
		// 			'serial_id' => $new_serial->id,
		// 			'user_id' => $user_id
		// 		]);
		// 	}
		// }
		
	}
