<?php

namespace App\Models;


class Department extends BaseModel
{
    //


    protected $guarded = [];

    protected $appends = [
        'locale_title'
    ];

    public function getLocaleTitleAttribute()
    {
        if (app()->isLocale('ar'))
            return $this->ar_title;

        return $this->title;

    }
}
