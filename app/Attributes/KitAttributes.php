<?php
	
	namespace App\Attributes;
	
	
	trait KitAttributes
	{
		public function addKitChildren($items)
		{
			foreach ($items as $kit_item){
				$obj = collect($kit_item)->only(['organization_id','qty','discount','tax','price','net','total','subtotal']);
				$obj['item_id'] = $kit_item['id'];
				$obj['creator_id'] = $this->creator_id;
				$items[] = $this->items()->create(collect($obj)->toArray());
			}
		}
		
		public function fillKitData($data)
		{
//			var_dump($data);
//			exit();
			$kit_data = [
				'total' => $data['total'],
				'subtotal' => $data['subtotal'],
				'tax' => $data['tax'],
				'discount' => $data['discount_value'],
				'net' => $data['net']
			];
			
			$this->data()->create($kit_data);
			
		}
		
		public function fetchKitData($qty,$kit_data)
		{

//			$source_data = $this->data()->first();
			
			$data['total'] = $kit_data['total'];
			$data['subtotal'] = $kit_data['subtotal'];
			$data['tax'] = $kit_data['tax'];
			$data['net'] = $kit_data['net'];
			$data['price'] = $kit_data['price'];
			
			
			return $data;
			
		}
		
	}
