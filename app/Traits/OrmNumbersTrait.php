<?php


namespace App\Traits;


trait OrmNumbersTrait
{
    public function moneyFormatter($money = 0)
    {
        return money_format('%.2n',$money);
    }
}