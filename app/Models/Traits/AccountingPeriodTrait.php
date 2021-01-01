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
        if (auth()->check()) {

            $isHasYearClosing = true; auth()->user()->organization->getConfig('IS_HAS_YEAR_CLOSING', 'ACCOUNTING');

            if ($isHasYearClosing && $isHasYearClosing == true) {
                $table = (new static)->getTable();
                if (Schema::hasColumn($table, 'organization_id')  && $table != 'invoice_items' ) {
                    $STARTAT = static::getAccountingStartYear(auth()->user());
                    $ENDAT =  static::getAccountingEndYear(auth()->user());
                    static::addGlobalScope(
                        'accountingPeriod',
                        function (Builder $builder) use ($table, $STARTAT, $ENDAT) {
                            $builder->whereDate("{$table}.created_at", '>=', $STARTAT)->whereDate("{$table}.created_at", '<=', $ENDAT);
                        }
                    );
                }
            }
        }
    }

    private static function getAccountingStartYear(Manager $manager)
    {
        $date = $manager->getConfig("START_YEAR_AT", "ACCOUNTING");
        if ($date)
            return Carbon::parse($date);

        return Carbon::parse('2021');
    }


    private static function getAccountingEndYear(Manager $manager)
    {
        $date = $manager->getConfig("END_YEAR_AT", "ACCOUNTING");

        if ($date)
            return Carbon::parse($date);


        return Carbon::parse('2022');
    }
}
