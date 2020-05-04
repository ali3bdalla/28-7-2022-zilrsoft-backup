<?php

namespace App;

use App\Attributes\OnlineUserAttribute;
use App\Attributes\UserAttributes;
use App\DatabaseHelpers\UserHelper;
use App\Relationships\UserRelationships;
use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{

    use SoftDeletes, UserRelationships, UserAttributes, UserHelper, Notifiable,HasApiTokens,OnlineUserAttribute;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        if (auth()->check()) {
            static::addGlobalScope(new OrganizationScope(auth()->user()->organization_id));
        }
    }


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

