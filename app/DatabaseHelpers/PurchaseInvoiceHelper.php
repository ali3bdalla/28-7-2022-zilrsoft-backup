<?php
	
	
	namespace App\DatabaseHelpers;
	
	
	trait PurchaseInvoiceHelper
	{
		public function created_new_item_serial_for_within_this_purchase_invoice(
			$source_request_item_data,
			$invoice_type,
			$user_id
		)
		{
			
			if (count($source_request_item_data) > 0){
				foreach ($source_request_item_data['serials'] as $serial){
					
					
					$new_serial = $this->serials()->create([
						'organization_id' => auth()->user()->organization_id,
						'creator_id' => auth()->user()->id,
						'purchase_invoice_id' => $this->invoice->id,
						'is_pending' => $invoice_type == 'pending_purchase' ? true : false,
						'item_id' => $source_request_item_data['id'],
						'serial' => $serial,
						'current_status' => 'available'
					]);
					
					
					if ($invoice_type != 'pending_purchase'){
						$this->invoice->serial_history()->create([
							'event' => $invoice_type,
							'organization_id' => auth()->user()->organization_id,
							'creator_id' => auth()->user()->id,
							'serial_id' => $new_serial->id,
							'user_id' => $user_id
						]);
					}


//
				}
			}
			
			
		}
	}