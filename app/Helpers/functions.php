<?php

if (!function_exists('roundMoney')) {

    function roundMoney($amount)
    {
        return round($amount * 10) / 10;
    }
}


if (!function_exists('moneyFormatter')) {
    function moneyFormatter($number, $decimal = 2)
    {
        return (float)number_format(round($number, $decimal), $decimal, '.', '');
    }
}


if (!function_exists('currencyMoneyFormatter')) {
    function currencyMoneyFormatter($money, $decimal = 2, $currency = "ريال")
    {
        return moneyFormatter($money, $decimal) . ' ' . $currency;
    }
}


if (!function_exists('displayMoney')) {
    function displayMoney($money, $decimal = 2)
    {
        return moneyFormatter((float)$money);
    }
}


if (!function_exists('displayAccountingMoney')) {
    function displayAccountingMoney($money, $decimal = 2)
    {
        $fmt = numfmt_create('en_US', 2);//NumberFormatter::
        return numfmt_format($fmt, $money, 3);
    }
}


if (!function_exists('generateOtp')) {

    function generateOtp()
    {
        return (int)(rand(1000, 9999));
    }
}


if (!function_exists('sendOtp')) {

    function sendOtp($phoneNumber, $otp)
    {
        $phoneNumber = '966' . $phoneNumber;
        if (app()->environment(['production', 'local'])) {

            sendSms(__('store.common.verification_code') . ' ' . $otp, $phoneNumber);
        }

    }
}


//	header("Content-Type: text/html; charset=utf-8");

if (!function_exists('sendSms')) {
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







