<?php

namespace App\Models;




class Configuration extends BaseModel
{

    protected $guarded = [];
    
    public function configurable()
    {
        return $this->morphTo('configurable');
    }
}
