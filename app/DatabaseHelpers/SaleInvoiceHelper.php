<?php
	
	
	namespace App\DatabaseHelpers;
	
	
	use App\ItemSerials;
	
	trait SaleInvoiceHelper
	{
		public function set_item_serials_status_as_paid_for_this_sale_invoice($serials,$status = "saled")
		{
			
			if ($status == "saled"){
				ItemSerials::whereIn('serial',$serials)->update(
					[
						'sale_invoice_id' => $this->invoice->id,
						'saled_by' => auth()->user()->id,
						'sale_at' => now(),
						'current_status' => 'saled'
					]
				);
				
				foreach ($serials as $serial){
					$serial_data = ItemSerials::where('serial',$serial)->first();
					$this->invoice->serial_history()->create([
						'event' => 'sale',
						'organization_id' => auth()->user()->organization_id,
						'creator_id' => auth()->user()->id,
						'serial_id' => $serial_data->id,
						'user_id' => $this->client_id
					]);
				}
			}else{
				ItemSerials::whereIn('serial',$serials)->update(
					[
						'r_sale_invoice_id' => $this->invoice->id,
						'current_status' => 'r_sale'
					]
				);
				
				foreach ($serials as $serial){
					$serial_data = ItemSerials::where('serial',$serial)->first();
					$this->invoice->serial_history()->create([
						'event' => 'sale',
						'organization_id' => auth()->user()->organization_id,
						'creator_id' => auth()->user()->id,
						'serial_id' => $serial_data->id,
						'user_id' => $this->client_id
					]);
				}
			}
			
			
		}
		
	}