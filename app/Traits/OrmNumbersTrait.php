<?php


namespace App\Traits;


trait OrmNumbersTrait
{
    public function moneyFormatter($money = 0)
    {
//        return $money;
        return number_format($money,2);
//        $formatter = \NumberFormatter::create('en_US',\NumberFormatter::MULTIPLIER);
//        return $formatter->format($money);
    }
}