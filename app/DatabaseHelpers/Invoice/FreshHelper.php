<?php
	
	
	namespace App\DatabaseHelpers\Invoice;
	
	
	use App\InvoiceItems;
	use App\Item;
	use App\Math\Math;
	use Dotenv\Exception\ValidationException;
	
	trait FreshHelper
	{
		use Math;
		
		/**
		 * @param $type
		 *
		 * @return mixed
		 */
		public static function initEmptyInvoice($type,$parentInvoice = null,$notes = "")
		{
			
			$creator = auth()->user();
			$object = new static();
			
			return $object->create([
				'invoice_type' => $type,
				'notes' => $notes,
				'creator_id' => $creator->id,
				'organization_id' => $creator->organization_id,
				'branch_id' => $creator->branch_id,
				'department_id' => $creator->department_id,
				'parent_invoice_id' => $parentInvoice == null ? 0 : $parentInvoice->id,
			]);
		}
		
		/**
		 * @param $user_id
		 * @param $base_invoice_type
		 *
		 * @return $this
		 */
		public function addChildInvoice($user_id,$base_invoice_type,$salesman_id = null,$parentInvoice = null,
		                                $alice_name = "")
		{
			if (in_array($base_invoice_type,['sale','r_sale'])){
				$creator = auth()->user();
				$this->sale()->create([
					'salesman_id' => $salesman_id == null ? $creator->id : $salesman_id,
					'client_id' => $user_id,
					'organization_id' => $creator->organization_id,
					'invoice_type' => $base_invoice_type,
					'alice_name' => $alice_name,
					"prefix" => $base_invoice_type == "sale" ? "SAI-" : "RSA-"
				]);
			}else{
				if ($base_invoice_type == 'purchase'){
					$prefix = 'PUI-';
				}elseif ($base_invoice_type == 'r_purchase'){
					$prefix = 'RUI-';
				}else{
					$prefix = 'BEG-';
				}
				$creator = auth()->user();
				$this->purchase()->create([
					'receiver_id' => $creator->id,
					'vendor_id' => $user_id,
					'organization_id' => $creator->organization_id,
					'invoice_type' => $base_invoice_type,
					"prefix" => $prefix
				]);
			}
			
			return $this;
		}
		
		/**
		 * @param $items
		 *
		 * @return $this
		 */
		public function addItemsToBaseInvoice($items)
		{
			
			foreach ($items as $item){
				$db_item = Item::findOrFail($item['id']);
				if ($db_item->is_kit)
					$created_item = $db_item->addKitToBaseInvoice($this,$item);
				else
					$created_item = $db_item->addToBaseInvoice($this,$item);
				
				
			}
			return $this;
		}
		
		/**
		 * @param $items
		 *
		 * @return FreshHelper
		 */
		public function addReturnedItemsToBaseInvoice($items)
		{
			foreach ($items as $item){
				if ($item['returned_qty'] >= 1){
					$db_item = Item::findOrFail($item['item_id']);
					$parent_item = InvoiceItems::findOrFail($item['id']);
					if ($db_item->is_kit)
						$db_item->addReturnedKitToBaseInvoice($this->fresh(),$item,$parent_item);
					else
						$db_item->addReturnedItemToBaseInvoice($this->fresh(),$item,$parent_item);
				}
				
				
			}
			return $this;
		}
		
		/**
		 * @return $this
		 */
		public function updateBaseInvoiceAccountingInformation()
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
			
			$paid = 0;
			foreach ($this->payments as $payment)
			{
				$paid+=$payment['amount'];
			}
			
			$result['remaining'] = $result['net'] - $paid;
			
			$this->update($result);
			
			return $this;
			
		}
		
		/**
		 * @param array $methods
		 * @param $user_id
		 * @param $net
		 * @param $items
		 * @param $expenses
		 * @param string $invoice_type
		 *
		 * @return $this
		 */
		public function createInvoiceTransactions($methods = [],$invoice_type = 'sale')
		{
			$items = $this->items()->where('belong_to_kit',false)->get();
			$invoice_status = $this->handle_invoice_transactions($methods,$this->user_id,$this->net,$items,[],$invoice_type);
			
			$this->update_invoice_creation_status($invoice_status);
			return $this;
		}
		
		public function createExpensesPurchases($expenses)
		{
			$invoices = [];
			foreach ($expenses as $request_item){
				$item = Item::findOrFail($request_item['id']);
				if ($item->is_expense){
					$invoices[] = $item->addPurchaseToExpense($request_item);
				}
			}
			return $this;
			
			
		}
		
	}