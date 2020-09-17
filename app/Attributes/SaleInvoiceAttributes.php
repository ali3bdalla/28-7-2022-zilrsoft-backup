<?php
	
	namespace App\Attributes;
	
	
	use App\Models\ItemSerials;
	
	trait SaleInvoiceAttributes
	{
//		public function setStatusOfSerialAsSaled($serials)
//		{
//			ItemSerials::whereIn('serial',$serials)->update(
//				[
//					'sale_invoice_id' => $this->invoice->id,
//					'saled_by' => auth()->user()->id,
//					'sale_at' => now(),
//					'current_status' => 'saled'
//				]
//			);
//
//			foreach ($serials as $serial){
//				$serial_data = ItemSerials::where('serial',$serial)->first();
//				$this->invoice->serial_history()->create([
//					'event' => 'sale',
//					'organization_id' => auth()->user()->organization_id,
//					'creator_id' => auth()->user()->id,
//					'serial_id' => $serial_data->id,
//					'user_id' => $this->client_id
//				]);
//			}
//
//
//		}
	}
