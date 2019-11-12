<?php
	
	
	namespace App\DatabaseHelpers;
	
	
	trait ChartPaymentHelper
	{
		public function gatewayRawBalance($old_balance)
		{
			return $this->gateway_debit_value - $this->gateway_credit_value + $old_balance;
		}
		
		
		
		public function getGatewayDebitValueAttribute()
		{
			if($this->payment_type=='receipt')
			{
				return $this->amount;
			}
			return 0;
		}
		
		
		public function getGatewayCreditValueAttribute()
		{
			
			if($this->payment_type=='payment')
			{
				return $this->amount;
			}
			return 0;
		}
	}