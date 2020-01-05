<?php
	
	
	namespace App\Accounting;
	
	
	use App\ItemSerials;
	use Dotenv\Exception\ValidationException;
	
	trait SerialTransactionAccounting
	{
		
		/**
		 * @param $list
		 * @param $qty
		 * @param $type
		 * @param null $incItem
		 */
		public function toValidateSerialArrayCurrentStatus($list,$qty,$type,$incItem = null)
		{
			
			if (in_array($type,['r_sale','r_purchase'])){
				$data = collect($list);
				if (!$data->has('serials') || empty($data['serials']))
					throw new ValidationException('item in package need to pass serials array');
				$userSideSerialsList = collect($data["serials"]);
				foreach ($userSideSerialsList->pluck("serial")->toArray() as $serial){
					// query to get serial form database
					$query = $incItem->item()->serials([
						['serial',$serial],
						['sale_invoice_id',$incItem->invoice_id],
					])->where()->first();
					
					if (empty($query))
						throw new ValidationException('serial is not validate');
					else{
						
						if ($type == 'r_sale'){
							if ($query['current_status'] != 'sale'){
								throw new ValidationException('serial is already returned');
							}
						}else{
							if (!in_array($query['current_status'],['available','r_sale'])){
								throw new ValidationException('serial is already returned');
							}
							
						}
						
					}
					
				}
				if (count($userSideSerialsList) !== $qty)
					throw new ValidationException('item serials not equal to returned qty');
				
				
			}else{
			
			}
		}
		
		/**
		 * @param $serials
		 * @param $inc
		 */
		public function toUpdateSerialArrayAsGivenType($serials,$inc)
		{
			
			if ($inc->invoice_type == "r_sale"){
				ItemSerials::whereIn('serial',$serials)->update(
					[
						'r_sale_invoice_id' => $inc->id,
						'current_status' => 'r_sale'
					]
				);
				
			}else if ($inc->invoice_type == 'r_purchase'){
				ItemSerials::whereIn('serial',$serials)->update(
					[
						'r_purchase_invoice_id' => $inc->id,
						'current_status' => 'r_purchase'
					]
				);
			}
			
			
			$this->toCreateSerialHistoryEvent($serials,$inc);
			
			
		}
		
		/**
		 * @param $serials
		 * @param $inc
		 */
		public function toCreateSerialHistoryEvent($serials,$inc)
		{
			foreach ($serials as $serial){
				$serial_data = ItemSerials::where('serial',$serial)->first();;
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