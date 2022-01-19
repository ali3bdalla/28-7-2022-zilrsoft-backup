<?php

namespace App\Models\Traits;

use App\Scopes\ActiveYearScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait AnnuallyScoped
{

    protected static function bootAnnuallyScoped()
    {
        static::addGlobalScope(new ActiveYearScope());
        if (Auth::user() && Auth::user()->active_year) {
            $sysActiveYear = Auth::user()->active_year;
            static::creating(function (Model $model) use ($sysActiveYear) {
                if (empty($model->invoice_id)) {
                    $creatingAt = Carbon::parse($model->created_at);
                    if ($creatingAt->year != $sysActiveYear)
                        $creatingAt = Carbon::createFromDate($sysActiveYear, '12', '31')->endOfDay();
                    $model->created_at = $creatingAt;
                    $model->updated_at = $creatingAt;
                }
            });
        }
    }
}
