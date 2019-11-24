<?php
	
	
	namespace App\DatabaseHelpers;
	
	
	trait UserHelper
	{
		
		private $total_clients_debit = 0;
		private $total_clients_credit = 0;
		private $clients_histories = [];
		
		public function to_client_invoices_history()
		{
			
			$invoices = $this->client_invoices;
			
			
			return $this->client_payments_invoice($invoices->pluck('invoice_id')->toArray());
//
			foreach ($invoices as $invoice){
				
				$this->hanle_client_single_history($invoice);
				
				if ($invoice['invoice']['current_status'] == 'paid'){
					$new_invoice = $invoice->replicate();;
					
					if ($new_invoice['invoice_type'] == 'sale')
						$new_invoice['invoice_type'] = 'r_sale';
					else
						$new_invoice['invoice_type'] = 'sale';
					$this->hanle_client_single_history($new_invoice);
				}
			}
			
			$result['data'] = $this->clients_histories;
			$result['total_credit'] = $this->total_clients_credit;
			$result['total_debit'] = $this->total_clients_debit;
			
			
			return $result;
			
		}
		
		public function hanle_client_single_history($invoice)
		{
			$invoice['debit'] = 0;
			$invoice['credit'] = 0;
			
			if ($invoice['invoice_type'] == 'sale')
				$invoice['debit'] = $invoice['invoice']['net'];
			else
				$invoice['credit'] = $invoice['invoice']['net'];

//			dd($invoice['debit']);
			
			$invoice['balance'] = $invoice['debit'] - $invoice['credit'];
			$this->total_clients_debit = $this->total_clients_debit + $invoice['debit'];
			$this->total_clients_credit = $this->total_clients_credit + $invoice['credit'];
			
			
			$this->clients_histories[] = $invoice;
		}
		
		public function update_vendor_balance($option,$amount)
		{
			$amount = floatval($amount);
			
			$old_balance = $this->vendor_balance;
			if ($option == 'add'){
				$this->update([
					'vendor_balance' => $old_balance + $amount
				]);
				return true;
			}else{
				
				$this->update([
					'vendor_balance' => $old_balance - $amount
				]);
				return true;
			}
		}
		
		public function update_client_balance($option,$amount)
		{
			$amount = floatval($amount);
			
			$old_balance = $this->vendor_balance;
			if ($option == 'add'){
				$this->update([
					'balance' => $old_balance + $amount
				]);
				return true;
			}else{
				
				$this->update([
					'balance' => $old_balance - $amount
				]);
				return true;
			}
		}
		
	}