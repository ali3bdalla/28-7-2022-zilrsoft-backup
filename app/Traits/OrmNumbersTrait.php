<?php


namespace App\Traits;


trait OrmNumbersTrait
{
    public function moneyFormatter($money = 0)
    {


        if($money == 0) return $money;
//        if($money < 1) return $money;
        return (float) sprintf('%.2f', floor($money*10000*($money>0?1:-1))/10000*($money>0?1:-1));

    }


    public function roundOnLessThan1Cent($number = 0)
    {
        return  abs(round($number) - $number) < 0.02 ? round($number) : $number;
    }
}