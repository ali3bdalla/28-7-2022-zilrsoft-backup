<?php

use App\ValueObjects\MoneyValueObject;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

if (!function_exists('shortLink')) {
    function shortLink($link = "")
    {
        return file_get_contents("http://tinyurl.com/api-create.php?url=$link");
    }
}

if (!function_exists('moneyFormatter')) {
    function moneyFormatter($amount): float
    {
        return (new MoneyValueObject($amount, __('store.products.sar')))->getAmount();
    }
}


if (!function_exists('generateOtp')) {
    function generateOtp(): int
    {
        return (rand(1000, 9999));
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

if (!function_exists('showMoney')) {
    function showMoney($money, $current = 'SAR'): string
    {
        $money = new Money(round($money * 100), new Currency($current));
        $currencies = new ISOCurrencies();
        $numberFormatter = new NumberFormatter('ar_SA', NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);
        return $moneyFormatter->format($money);
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

