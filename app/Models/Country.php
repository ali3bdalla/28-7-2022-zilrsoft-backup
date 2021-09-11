<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends BaseModel
{

    protected $guarded = [];

    protected $appends = ['locale_name'];

    public function organizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'country_id');
    }
    //
}


