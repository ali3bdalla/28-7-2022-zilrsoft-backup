<?php

namespace App\Models;

class FilterValues extends BaseModel
{
    use \App\Traits\OrganizationTarget;

    protected $guarded = [];

    protected $appends = [
        'locale_name'
    ];
    //


    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id');
        # code...
    }

    public function getLocaleNameAttribute()
    {

        if (app()->isLocale('ar')) {
            return $this->ar_name;
        }

        return $this->name;
    }

    public function setAsLastUsedValue()
    {
        $this->update([
            'updated_at' => now()
        ]);
        # code...
    }



    public function filter()
    {
        return $this->belongsTo(Filter::class, 'filter_id');
    }


    public function manager()
    {
        return $this->belongsTo(Manager::class, 'creator_id');
    }
}
