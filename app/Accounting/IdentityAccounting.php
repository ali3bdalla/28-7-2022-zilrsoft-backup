<?php
	
	
	namespace App\Accounting;
	
	
	trait IdentityAccounting
	{
		/**
		 * @param $identity
		 * @param $option
		 * @param $amount
		 *
		 * @return bool
		 */
		public function toUpdateVendorBalance($identity,$option,$amount)
		{
			$amount = floatval($amount);
			$old_balance = $identity->vendor_balance;
			if ($option == 'plus'){
				$identity->update([
					'vendor_balance' => $old_balance + $amount
				]);
				return true;
			}else{
				$identity->update([
					'vendor_balance' => $old_balance - $amount
				]);
				return true;
			}
			return false;
		}
		
		/**
		 * @param $identity
		 * @param $option
		 * @param $amount
		 *
		 * @return bool
		 */
		public function toUpdateClientBalance($identity,$option,$amount)
		{
			$amount = floatval($amount);
			$old_balance = $identity->balance;
			if ($option == 'plus'){
				$identity->update([
					'balance' => $old_balance + $amount
				]);
				return true;
			}else{
				$identity->update([
					'balance' => $old_balance - $amount
				]);
				return true;
			}
			
			return false;
		}
		
	}