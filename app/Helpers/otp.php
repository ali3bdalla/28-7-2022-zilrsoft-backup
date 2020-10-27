<?php
	
	
	if(!function_exists('generateOtp')) {
		
		function generateOtp()
		{
			return (int)(rand() * 1000 * rand());
		}
	}
	
	
	
	
	if(!function_exists('sendOtp')) {
		
		function sendOtp($phoneNumber,$otp)
		{
		
		
		}
	}
	
	