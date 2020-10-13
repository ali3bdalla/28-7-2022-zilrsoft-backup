<?php

if (!function_exists('roundMoney')) {
    
    function roundMoney($amount)
    {
        return round($amount * 10) / 10;
    }
}


if (!function_exists('moneyFormatter')) {
    function moneyFormatter($money, $decimal = 2)
    {
        return money_format("%i", $money);
    }
}






