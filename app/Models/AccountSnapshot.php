<?php

namespace App\Models;

use App\Models\Traits\AccountingPeriodTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountSnapshot extends BaseModel
{
    use SoftDeletes;
    use AccountingPeriodTrait;

    protected $guarded = [];

    

    public function account()
    {
        return  $this->belongsTo(Account::class,'account_id');
    }
}
