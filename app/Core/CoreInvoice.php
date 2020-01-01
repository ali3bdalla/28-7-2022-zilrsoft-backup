<?php
	
	namespace App\Core;
	
	
	use App\InvoiceItems;
	use Dotenv\Exception\ValidationException;
	
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
			
			return $object->create([
				'invoice_type' => $user_data['invoice_type'],
				'creator_id' => auth()->user()->id,
				'organization_id' => auth()->user()->organization_id,
				'branch_id' => auth()->user()->branch_id,
				'department_id' => auth()->user()->department_id,
				'parent_invoice_id' => $user_data['parent_id'] == null ? 0 : $user_data['parent_id']
			]);
			
		}
		
		/**
		 * @param string $table
		 * @param array $info
		 *  push subinvoice
		 *
		 * @return mixed
		 */
		public function publishSubInvoice($table = 'sale',$info = [])
		{
			
			$info['organization_id'] = auth()->user()->organization_id;
			if ($table === 'sale'){
				return $this->sale()->create($info);
			}else{
			
			}
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
			}else{
//				
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
					
					}else{
						$result_items[] = $incItem->addQtyReturn($item,$this->fresh());
					}
					
				}
				
			}
			
			return $result_items;
		}
		
		/**
		 * @return $this
		 */
		public function runAccountingAmountUpdater()
		{
			$result['total'] = 0;
			$result['subtotal'] = 0;
			$result['tax'] = 0;
			$result['discount_value'] = 0;
			$result['net'] = 0;
			
			foreach ($this->items as $item){
				if (!$item->item->is_kit){
					if (!$item['belong_to_kit']){
						$result['total'] = $result['total'] + $item['total'];
						$result['discount_value'] = $result['discount_value'] + $item['discount'];
					}
					$result['subtotal'] = $result['subtotal'] + $item['subtotal'];
					$result['tax'] = $result['tax'] + $item['tax'];
					$result['net'] = $result['net'] + $item['net'];
				}
			}
			
			
			$this->update($result);
			return $this;
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