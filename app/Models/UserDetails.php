<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\HasOne;

class UserDetails extends BaseModel
{
    protected $guarded = [];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'user_id');
    }
}
