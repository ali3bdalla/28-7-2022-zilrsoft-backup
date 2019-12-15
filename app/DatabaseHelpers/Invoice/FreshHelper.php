<?php
	
	
	namespace App\DatabaseHelpers\Invoice;
	
	
	use App\Http\Requests\Invoice\PurchaseCreationRequest;
	use App\InvoiceItems;
	use App\Item;
	use App\Math\Math;
	
	trait FreshHelper
	{
		use Math;
		
		/**
		 * @param $type
		 *
		 * @return mixed
		 */
		public static function initEmptyInvoice($type,$parentInvoice = null)
		{
			
			$creator = auth()->user();
			$object = new static();
			
			return $object->create([
				'invoice_type' => $type,
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
		public function addChildInvoice($user_id,$base_invoice_type,$parentInvoice = null)
		{
			$creator = auth()->user();
			$this->sale()->create([
				'salesman_id' => $creator->id,
				'client_id' => $user_id,
				'organization_id' => $creator->organization_id,
				'invoice_type' => $base_invoice_type,
				"prefix" => $base_invoice_type == "sale" ? "SAI-" : "RSA-"
			]);
			return $this;
		}
		
		/**
		 * @param $items
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
		 */
		public function addReturnedItemsToBaseInvoice($items)
		{
			foreach ($items as $item){
				if ($item['returned_qty'] >= 1){
					$db_item = Item::findOrFail($item['item_id']);
					$parent_item = InvoiceItems::findOrFail($item['id']);
					
					if ($db_item->is_kit)
						$created_item = $db_item->addReturnedKitToBaseInvoice($this,$item,$parent_item);
					else
						$created_item = $db_item->addReturnedItemToBaseInvoice($this,$item,$parent_item);
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