<?php

namespace App;

use App\Attributes\OnlineUserAttribute;
use App\Attributes\UserAttributes;
use App\DatabaseHelpers\UserHelper;
use App\Relationships\UserRelationships;
use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends BaseAuthModel
{

    use SoftDeletes, UserRelationships, UserAttributes, UserHelper;

    protected $guarded = [];



    protected $appends = [
        'locale_name'
    ];
    protected $casts = [
        'is_vendor' => 'boolean',
        'is_client' => 'boolean',
        'is_supplier' => 'boolean',
        'can_make_credit' => 'boolean',
        'is_supervisor' => 'boolean',
        'is_manager' => 'boolean',
    ];

    protected $hidden = [
        'password'
    ];

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }


}

