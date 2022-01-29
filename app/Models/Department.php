<?php

namespace App\Models;


/**
 * @property mixed title
 * @property mixed ar_title
 */
class Department extends BaseModel
{
    use \App\Traits\OrganizationTarget;
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
