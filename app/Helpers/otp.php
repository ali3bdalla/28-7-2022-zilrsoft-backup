<?php
	
	
	if(!function_exists('generateOtp')) {
		
		function generateOtp()
		{
			return (int)(rand(1000, 9999));
		}
	}
	
	
	if(!function_exists('sendOtp')) {
		
		function sendOtp($phoneNumber, $otp)
		{
			$phoneNumber = '966' . $phoneNumber;
            if (app()->environment(['production', 'local'])) {

                sendSms(__('store.common.verification_code') . ' '. $otp, $phoneNumber);
            }

		}
	}
	
	