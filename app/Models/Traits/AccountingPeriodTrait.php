<?php

namespace App\Models\Traits;

use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait AccountingPeriodTrait
{


    protected static function bootAccountingPeriodTrait()
    {
        // if (auth('manager')->check()) {

        //     $isHasYearClosing = true; auth()->user()->organization->getConfig('IS_HAS_YEAR_CLOSING', 'ACCOUNTING');

        //     if ($isHasYearClosing && $isHasYearClosing == true) {
        //         $table = (new static)->getTable();
        //         if (Schema::hasColumn($table, 'organization_id')  && $table != 'invoice_items' ) {
        //             $STARTAT = static::getAccountingStartYear(auth()->user());
        //             $ENDAT =  static::getAccountingEndYear(auth()->user());

        //             static::addGlobalScope(
        //                 'accountingPeriod',
        //                 function (Builder $builder) use ($table, $STARTAT, $ENDAT) {
        //                     $builder->whereYear("{$table}.created_at", '>=', $STARTAT)->whereYear("{$table}.created_at", '<', $ENDAT);

        //                 }
        //             );
        //         }
        //     }
        // }
    }

    private static function getAccountingStartYear(Manager $manager)
    {
        $date = $manager->getConfig("START_YEAR_AT", "ACCOUNTING",false);
        if ($date)
            return Carbon::createFromDate($date)->format("Y");


        return Carbon::createFromDate('2021')->format("Y");
    }


    private static function getAccountingEndYear(Manager $manager)
    {
        $date = $manager->getConfig("END_YEAR_AT", "ACCOUNTING",false);

        if ($date)
            return Carbon::createFromDate($date)->format("Y");


        return Carbon::createFromDate('2022')->format("Y");
    }
}
