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
			$kit_data = [
				'total' => $data['total'],
				'subtotal' => $data['subtotal'],
				'tax' => $data['tax'],
				'net' => $data['net']
			];
			
			$this->data()->create($kit_data);
			
		}
		
		public function fetchKitData($qty,$kit_data)
		{
			
			$source_data = $this->data()->first();
			
			$data['total'] = $source_data['total'] * $qty;
			$data['subtotal'] = $source_data['subtotal'] * $qty;
			$data['tax'] = $source_data['tax'] * $qty;
			$data['net'] = $source_data['net'] * $qty;
			$data['price'] = $data['total'] / $qty;
			
			
			return $data;
			
		}
		
	}
