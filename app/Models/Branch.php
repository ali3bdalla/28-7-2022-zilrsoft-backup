<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed ar_name
 * @property mixed name
 */
class Branch extends BaseModel
{
    use \App\Traits\OrganizationTarget;
    protected $guarded = [];

    protected $appends = [
        'locale_name'
    ];


    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar'))
            return $this->ar_name;

        return $this->name;
    }


    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, 'branch_id');
    }
}
