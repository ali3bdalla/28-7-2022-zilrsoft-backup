<?php
	
	
	namespace App\Accounting;
	
	
	use AliAbdalla\Tafqeet\Core\Tafqeet;
	
	trait PaymentAccounting
	{
		/**
		 * @param $inc
		 * @param $amount
		 * @param $payment_type
		 * @param $gateway
		 */
		public function toCreateInvoicePayment($inc,$amount,$payment_type,$gateway)
		{
			if ($gateway != null){
				
				$payment = $gateway->paymentable()->create([
					'organization_id' => $inc->organization_id,
					'creator_id' => $inc->creator_id,
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
					'amount_ar_words' => Tafqeet::arablic($amount),
					'amount_en_words' => Tafqeet::arablic($amount),
					'amount' => $amount,
					'payment_type' => $payment_type
				]);
			}else{
				$payment = $inc->payments()->create([
					'organization_id' => $inc->organization_id,
					'creator_id' => $inc->creator_id,
					'user_id' => $inc->user_id,
					'invoice_id' => $inc->id,
					'amount_ar_words' => Tafqeet::arablic(($amount)),
					'amount_en_words' => Tafqeet::arablic(($amount)),
					'amount' => $amount,
					'payment_type' => $payment_type
				]);
			}
			
			
			$inc->invoice_payments()->create([
				'organization_id' => $inc->organization_id,
				'creator_id' => $inc->creator_id,
				'payment_id' => $payment->id,
				'amount' => $amount,
			]);
//			}
		}
	}