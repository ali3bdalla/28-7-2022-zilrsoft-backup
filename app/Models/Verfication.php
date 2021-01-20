<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verfication extends BaseModel
{
    protected $guarded = [];
    //

    public function verifiable()
    {
        return $this->morphTo('verifiable');
    }
}
