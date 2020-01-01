<?php
	
	
	namespace App\Core;
	
	
	use App\ItemSerials;
	
	trait CoreItemSerials
	{
		/**
		 * @param $serials
		 * @param $inc
		 */
		public static function markReturnAs($serials,$inc)
		{
			
			if ($inc->invoice_type == "r_sale"){
				ItemSerials::whereIn('serial',$serials)->update(
					[
						'r_sale_invoice_id' => $inc->id,
						'current_status' => 'r_sale'
					]
				);
				
			}else{
				ItemSerials::whereIn('serial',$serials)->update(
					[
						'r_purchase_invoice_id' => $inc->id,
						'current_status' => 'r_purchase'
					]
				);
			}
			
			
			self::addHistoryEvent($serials,$inc);
			
			
		}
		
		/**
		 * @param $serials
		 * @param $inc
		 */
		public static function addHistoryEvent($serials,$inc)
		{
			foreach ($serials as $serial){
				$serial_data = ItemSerials::where('serial',$serial)->first();
				
				;
				$serial_data->histories()->create([
					'event' => $inc->invoice_type,
					'invoice_id' => $inc->id,
					'organization_id' => auth()->user()->organization_id,
					'creator_id' => auth()->user()->id,
					'serial_id' => $serial_data->id,
					'user_id' => $inc->user_id
				]);
			}
		}
	}