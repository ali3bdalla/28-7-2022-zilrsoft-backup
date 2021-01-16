<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends BaseModel
{
    //
    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }
}
