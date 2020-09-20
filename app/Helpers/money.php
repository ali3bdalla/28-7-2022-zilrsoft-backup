<?php

if (!function_exists('roundMoney')) {
    function roundMoney($amount)
    {
        // return $amount;
        // return round($amount);
        return round($amount * 10) / 10;
    }
}



if (!function_exists('moneyFormatter')) {
    function moneyFormatter($money)
    {
        return money_format("%i",$money);
    }
}






