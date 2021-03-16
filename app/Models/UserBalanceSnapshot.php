<?php

namespace App\Models;

use App\Models\Traits\AccountingPeriodTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserBalanceSnapshot extends Model
{
    use SoftDeletes;
    use AccountingPeriodTrait;

    protected $guarded = [];


    public function user()
    {
        return  $this->belongsTo(User::class,'user_id');
    }
    public function account()
    {
        return  $this->belongsTo(Account::class,'account_id');
    }
}
