<?php

if (!function_exists('roundMoney')) {
    function roundMoney($amount)
    {
        // return $amount;
        // return round($amount);
        return round($amount * 10) / 10;
    }
}



