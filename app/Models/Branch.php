<?php

namespace App\Models;

use App\Scopes\OrganizationScope;

class Branch extends BaseModel
{
    //


    protected $guarded = [];

    protected $appends = [
        'locale_name'
    ];

    protected static function boot()
    {
        parent::boot();
        if (auth()->check()) {
            if (!empty(auth()->user()->organization_id)) {
                static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
            }
        }
    }

    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar'))
            return $this->ar_name;

        return $this->name;
    }


    public function departments()
    {
        return $this->hasMany(Department::class, 'branch_id');
    }

}
