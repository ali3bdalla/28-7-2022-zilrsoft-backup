<?php
	
	namespace App\Core;
	
	
	use App\InvoiceItems;
	use App\Item;
	
	trait CoreInvoice
	{
		
		/**
		 * @param array $user_data
		 * create new base invoice
		 *
		 * @return mixed
		 */
		public static function publish($user_data = [])
		{
			$object = new self();
			
			$data = collect($user_data);
			return $object->create([
				'invoice_type' => $user_data['invoice_type'],
				'notes' => collect($user_data)->has('notes') ? $user_data['notes'] : "",
				'creator_id' => auth()->user()->id,
				'organization_id' => auth()->user()->organization_id,
				'branch_id' => auth()->user()->branch_id,
				'department_id' => auth()->user()->department_id,
				'parent_invoice_id' => $user_data['parent_id'] == null ? 0 : $user_data['parent_id'],
				'is_deleted' => $data->has('is_deleted') ? $user_data['is_deleted'] : 0
			]);
			
		}
		
		/**
		 * @param string $table
		 * @param array $info *
		 *
		 * @return
		 */
		public function publishSubInvoice($table = 'sale',$info = [])
		{

//			return $info;
			$info['organization_id'] = auth()->user()->organization_id;
			if ($table === 'sale'){
				return $invoice = $this->sale()->create($info);
			}else{
				$invoice = $this->purchase()->create($info);
			}
			return $invoice;
		}
		
		/**
		 * @param array $items
		 *
		 * @return array
		 */
		public function pushItems($items = [])
		{
			$result_items = [];
			if (in_array($this->invoice_type,['r_sale','r_purchase'])){
				$itemsList = [];
				foreach ($items as $item){
					if ($item['returned_qty'] >= 1){
						$itemsList[] = $item;
					}
				}
				$result_items = $this->pushReturnItems($itemsList);
			}else if ($this->invoice_type == 'stock_adjust'){
				$result_items = $this->pushAdjustStockItems($items);
			}else{
//				$result_items = $this->p2ushFreshInvoiceItems($items);
			}
			
			
			return $result_items;
		}
		
		/**
		 * @param array $itemsList
		 *
		 * @return array
		 */
		public function pushReturnItems($itemsList = [])
		{
			
			$result_items = [];
			foreach ($itemsList as $item){
				$incItem = InvoiceItems::findOrFail($item['id']);
				if (!$incItem['belong_to_kit']){
					if ($incItem['is_kit']){
						$result_items[] = $incItem->preformKitReturn($item,$this->fresh());
					}else{
						$result_items[] = $incItem->preformItemReturn($item,$this->fresh());
					}
				}
				
			}
			
			return $result_items;
		}
		
		public function pushAdjustStockItems($itemsList = [])
		{
			$result_items = [];
			foreach ($itemsList as $item){
				$freshItem = Item::findOrFail($item['id']);
				$result_items[] = $freshItem->performAdjustStockItem($item,$this->fresh());
				
			}
			
			return $result_items;
		}
		
		/**
		 * @param array $items
		 */
		public function pushFreshInvoiceItems($items = [])
		{
			if ($this->invoice_type == 'sale'){
			
			}else{
			
			}
		}
		
		/**
		 * @param array $methods
		 * @param string $invoice_type
		 *
		 * @return $this
		 */
		public function pushTransactions($methods = [],$invoice_type = 'sale')
		{
			$items = $this->items()->where('belong_to_kit',false)->get();
			$invoice_status = $this->handle_invoice_transactions($methods,$this->user_id,$this->net,$items,[],$invoice_type);
			$this->update_invoice_creation_status($invoice_status);
			return $this;
		}
		
		/**
		 * @return $this
		 */
		public function updateReturnStatus()
		{
			$result['is_updated'] = true;
			$result['is_deleted'] = true;
			$items = $this->items;
			foreach ($items as $item){
				if ($item['qty'] > $item['r_qty']){
					$result['is_deleted'] = false;
				}
			}
			
			$this->update($result);
			return $this;
		}
	}