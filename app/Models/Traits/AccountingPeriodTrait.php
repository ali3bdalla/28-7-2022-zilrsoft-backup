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


        // $oganization->addConfig(true, 'IS_HAS_YEAR_CLOSING', null, 'boolean', 'ACCOUNTING');
        // $oganization->addConfig('01/01', 'YEAR_STARTING_AT', 'Starting Year At', 'date', 'ACCOUNTING');
        // $oganization->addConfig('31/12', 'YEAR_CLOSEING_AT', 'Closing Year At', 'date', 'ACCOUNTING');
        // $oganization->addConfig(106, 'TARGET_INCOMES_EXPENSES_NORMALIZATION_ACCOUNT', null, 'integer', 'ACCOUNTING');
        // $oganization->addConfig("2018", 'LAST_YEAR_CLOSED', null, 'date', 'ACCOUNTING');
        if (auth()->check()) {

            $isHasYearClosing = true; auth()->user()->organization->getConfig('IS_HAS_YEAR_CLOSING', 'ACCOUNTING');

            if ($isHasYearClosing && $isHasYearClosing == true) {
                $table = (new static)->getTable();
                if (Schema::hasColumn($table, 'organization_id')) {
                    $STARTAT = static::getAccountingStartYear(auth()->user());
                    $ENDAT =  static::getAccountingEndYear(auth()->user());
                    static::addGlobalScope(
                        'accountingPeriod',
                        function (Builder $builder) use ($table, $STARTAT, $ENDAT) {
                            $builder->whereYear("{$table}.created_at", '>=', $STARTAT)->whereYear("{$table}.created_at", '<', $ENDAT);
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

        return Carbon::parse('2022');
    }


    private static function getAccountingEndYear(Manager $manager)
    {
        $date = $manager->getConfig("END_YEAR_AT", "ACCOUNTING");
        if ($date)
            return Carbon::parse($date);


        return Carbon::parse('2021');
    }
}
