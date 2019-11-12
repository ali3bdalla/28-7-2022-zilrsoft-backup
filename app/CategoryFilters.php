<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Scopes\OrganizationScopeForRelationships;


class CategoryFilters extends Model
{

	protected static function boot()
    {
        parent::boot();
         if(auth()->check()){
            static::addGlobalScope(new OrganizationScopeForRelationships(auth()->user()->organization_id));
         }
    }

    //
}
