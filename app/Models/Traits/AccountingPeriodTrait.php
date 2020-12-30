<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait AccountingPeriodTrait
{


    protected static function bootAccountingPeriodTrait()
    {
        $table = (new static)->getTable();
        // if(Schema::hasColumn($table, 'organization_id')) {
            $accountingYear =self::getAccountingYear();
            // static::addGlobalScope(
            //     'accountingPeriod', function(Builder $builder) use ($table,$accountingYear) {
            //     $builder->whereYear("{$table}.created_at",$accountingYear );
            // }
            // );
        // }

    }

    public static function getAccountingYear()
    {
        return Carbon::now();
        // ->format("Y");
    }
}
