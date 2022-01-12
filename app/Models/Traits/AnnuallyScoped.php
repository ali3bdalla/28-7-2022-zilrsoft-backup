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
            $activeYear = Auth::user()->active_year;
            static::creating(function (Model $model) use ($activeYear) {
                if (!$model->invoice_id) {
                    $creatingAt = Carbon::parse($model->created_at);
                    if ($creatingAt->year != $activeYear)
                        $model->created_at = Carbon::createFromDate($activeYear, '12', '31');
                }
            });
        }
    }

}
