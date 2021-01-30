<?php

namespace App;

use App\Models\BaseModel;
use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class ItemTag extends BaseModel
{
    protected $guarded = [];


    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }



    //
}
