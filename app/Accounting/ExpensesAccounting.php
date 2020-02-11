<?php
	
	
	namespace App\Accounting;
	
	
	use App\Invoice;
	use App\Item;
	
	trait ExpensesAccounting
	{
		
		/**
		 * @param array $expenses
		 *
		 * @return array
		 */
		public function toCreatePurchaseInvoiceForExpensesItems(Invoice $inc,$expenses = [])
		{
			$invoices = [];
			foreach ($expenses as $itemData){
				$item = Item::findOrFail($itemData['id']);
				if ($item->is_expense){
					$invoices[] = $this->toCreateSingleExpenseItemPurchaseInvoice($inc,$item,$itemData);
				}
			}
			return $invoices;
		}
		
		/**
		 * @param Invoice $inc
		 * @param Item $item
		 * @param array $ItemData
		 *
		 * @return mixed
		 */
		private function toCreateSingleExpenseItemPurchaseInvoice(Invoice $inc,Item $item,$ItemData = [])
		{
			$itemAccounting = new ItemAccounting();
			
			
			$dbData = collect($item)->toArray();
			$dbData['net'] = $ItemData['purchase_price'];
			$dbData['subtotal'] = $dbData['net'] / (1 + ($item->vtp / 100)); // 0.05 + 1 = 1.05
			$dbData['qty'] = 1;
			$dbData['tax'] = $dbData['net'] - $dbData['subtotal'];
			$dbData['discount'] = 0;
			$dbData['total'] = $dbData['subtotal'];

//			$dbData['tax'] = $itemAccounting->getTaxAmount($dbData['total'],$item->vtp);
//
//			$dbData['net'] = $itemAccounting->getNetAmount($dbData['subtotal'],$dbData['tax']);
			$dbData['cost'] = $ItemData['purchase_price'];
			$dbData['purchase_price'] = $dbData['total'];
//			$cashGateway = auth()->user()->toGetManagerAccount('gateway');
//			$cashGateway->amount = $dbData['net'];
//			$cashGateway->is_paid = true;
			$invoice = Invoice::publish(
				[
					'invoice_type' => 'purchase',
					'parent_id' => $inc->id,
				]
			);
			
			$purchase = $invoice->publishSubInvoice('purchase',[
				'invoice_type' => 'purchase',
				'prefix' => 'PUI-',
				'vendor_inc_number' => '000000000',
				'vendor_id' => $item->expense_vendor_id,
				'receiver_id' => $inc->sale->salesman_id
			]);
//			echo $dbData['expense_vendor_id'];
			
			$itemsList = [collect($dbData)->toArray()];
			$gatewaysList = [];
			$invoice->add_items_to_invoice($itemsList,$purchase,[],'purchase',$dbData['expense_vendor_id']);
//
			$this->toGetAndUpdatedAmounts($invoice);
			$this->toCreateInvoiceTransactions($invoice,$itemsList,$gatewaysList,[]);
			return $invoice;
		}
		
		/**
		 * @param Invoice $invoice
		 *
		 * @return mixed
		 */
		public function toGetPurchaseExpenses(Invoice $invoice)
		{
			
			return $invoice->expenses()->with('expense')->get();
		}
		
		/**
		 * @param $inc
		 * @param array $items
		 * @param array $expenses
		 *
		 * @return float|int|mixed
		 */
		public function toCreateInvoiceExpenseAndGetTotal($inc,$items = [],$expenses = [])
		{
			
			
			$total_taxes = 0;
			foreach ($expenses as $expense){
				foreach ($items as $item){
					$new_item = Item::find($item['id']);
					$amount = $expense['amount'] * $item['widget'] / (1 + $item['vtp'] / 100); //
					$tax = $expense['amount'] - $amount;
					$total_taxes = $total_taxes + $tax;
				}
				
				
				$org_vat = auth()->user()->organization->organization_vat;
				$expense_tax = $expense['amount'] * $org_vat / (100 + $org_vat);
				$inc->expenses()->create(
					[
						'expense_id' => $expense['id'],
						'amount' => $expense['amount'],
						'tax' => $expense_tax,
						'with_net' => $expense['is_apended_to_net'],
					]
				);
			}
			return $total_taxes;
		}
		
	}