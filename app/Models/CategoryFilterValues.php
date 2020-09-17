<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryFilterValues extends Model
{
    protected $guarded =[];

    public function filter()
    {
        return $this->belongsTo(Filter::class,'filter_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function value()
    {
        return $this->belongsTo(FilterValues::class,'value_id');
    }
}
