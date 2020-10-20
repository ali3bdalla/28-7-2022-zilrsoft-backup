<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class AccountSnapshot extends BaseModel
{
    use SoftDeletes;
    protected $guarded = [];

    

    public function account()
    {
        return  $this->belongsTo(Account::class,'account_id');
    }
}
