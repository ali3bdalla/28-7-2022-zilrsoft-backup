<?php


namespace App\Models\Traits;


use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTimeInterface;
use Illuminate\Support\Facades\Date;

trait PostgresTimestamp
{
    public function freshTimestamp(): Carbon
    {
        return Carbon::now();
    }

    /**
     * @param $value
     * @return Carbon|\Illuminate\Support\Carbon
     */
    protected function asDateTime($value)
    {
        if ($value instanceof CarbonInterface) {
            return Date::instance($value);
        }

        if ($value instanceof DateTimeInterface) {
            return Date::parse(
                $value->format('Y-m-d H:i:s.u'), $value->getTimezone()
            );
        }
        if (is_numeric($value)) {
            return Date::createFromTimestamp($value);
        }


        if ($this->isStandardDateFormat($value)) {
            return Date::instance(\Illuminate\Support\Carbon::createFromFormat('Y-m-d', $value)->startOfDay());
        }

        return Carbon::parse($value);
    }


}
