<?php
	
	if(!function_exists('roundMoney')) {
		
		function roundMoney($amount)
		{
//        return $amount;
			return round($amount * 10) / 10;
		}
	}
	
	
	if(!function_exists('moneyFormatter')) {
		function moneyFormatter($money, $decimal = 2)
		{
			return money_format("%i", $money);
		}
	}
	
	
	if(!function_exists('displayMoney')) {
		function displayMoney($money, $decimal = 2)
		{
//			round($money,2)
			return money_format("%i",$money);
			$fmt = numfmt_create('en_US', NumberFormatter::DECIMAL);
			return numfmt_format($fmt, $money);

//			return roundMoney($money);
		}
	}
	





