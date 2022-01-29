<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountSnapshot extends BaseModel
{
    use SoftDeletes;
    use \App\Traits\OrganizationTarget;
    protected $guarded = [];


    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
