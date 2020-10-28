<?php
	header("Content-Type: text/html; charset=utf-8");
	
	if(!function_exists('sendSms')) {
		function sendSms($messageContent, $mobileNumber)
		{
			$text = urlencode($messageContent);
			$to = $mobileNumber;
			$url = "http://www.oursms.net/api/sendsms.php?username=" . config('services.sms.username') . "&password=" . config('services.sms.password') . "&numbers=$to&message=$text&sender=" . config('services.sms.send_name') . "&unicode=E&return=full";
			return file_get_contents($url);
		}
	}
	
	
	// auth call
	
	//لارجاع القيمه json
	//$url = "http://www.oursms.net/api/sendsms.php?username=$user&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E&return=json";
	// لارجاع القيمه xml
	//$url = "http://www.oursms.net/api/sendsms.php?username=$user&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E&return=xml";
	// لارجاع القيمه string
	//$url = "http://www.oursms.net/api/sendsms.php?username=$user&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E";
	// Call API and get return message
	//fopen($url,"r");
	     