<?php

namespace App\Models;


class Filter extends BaseModel
{
    //

    protected $appends = [
        'locale_name',
    ];
    protected $guarded = [
    ];

    protected $casts = [
        'is_required_filter' => 'boolean'
    ];

    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar')) {
            return $this->ar_name;
        }


        return $this->name;
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id')->withTrashed();
    }


    public function categories()
    {
        return $this->belongsToMany(Filter::class, 'category_filters', 'filter_id', 'category_id')->withTimestamps();
    }

    public function values()
    {
        return $this->hasMany(FilterValues::class, 'filter_id')->orderByDesc('filter_values.updated_at');
    }

}
