<?php


namespace App\Traits;


trait OrmNumbersTrait
{
    public function moneyFormatter($money = 0)
    {

        return (float)number_format($money, 2, '.', '.');
//        return number_format($money,2);
//        $formatter = \NumberFormatter::create('en_US',\NumberFormatter::CURRENCY);
//        return $formatter->format($money);
    }
}