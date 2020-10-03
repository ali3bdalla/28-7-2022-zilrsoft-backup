<?php

namespace App\Models;

use App\Relationships\KitItemsRelationships;
use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Model;

class KitItems extends Model
{

    use KitItemsRelationships;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        if (auth()->check()) {
            static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
        }
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }


    public function kit()
    {
        return $this->belongsTo(Item::class, 'kit_id');
    }

    //
}
