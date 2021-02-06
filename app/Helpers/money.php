<?php
	
	if(!function_exists('roundMoney')) {
		
		function roundMoney($amount)
		{
			return round($amount * 10) / 10;
		}
	}
	
	
	if(!function_exists('moneyFormatter')) {
		function moneyFormatter($money, $decimal = 2)
		{
			return number_format($money, 2,'.','.');			
		}
	}
	if(!function_exists('currencyMoneyFormatter')) {
		function currencyMoneyFormatter($money, $decimal = 2,$currency = "ريال")
		{
			return number_format($money, 2,'.','.') . ' ' . $currency;			
		}
	}
	
	
	if(!function_exists('displayMoney')) {
		function displayMoney($money, $decimal = 2)
		{
			return moneyFormatter( $money);
		}
	}
	
	
	if(!function_exists('displayAccountingMoney')) {
		function displayAccountingMoney($money, $decimal = 2)
		{
			$fmt = numfmt_create('en_US', 2);//NumberFormatter::
			return numfmt_format($fmt, $money, 3);
		}
	}
	





