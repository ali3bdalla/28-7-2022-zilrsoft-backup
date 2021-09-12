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
        return floatval(number_format(round($number, $decimal), $decimal, '.', ''));
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


if (!function_exists('sendSms')) {
    function sendSms($messageContent, $mobileNumber)
    {
        $text = urlencode($messageContent);
        $to = $mobileNumber;
        $url = "http://www.oursms.net/api/sendsms.php?username=" . config('services.sms.username') . "&password=" . config('services.sms.password') . "&numbers=$to&message=$text&sender=" . config('services.sms.send_name') . "&unicode=E&return=full";
        return file_get_contents($url);
    }
}

