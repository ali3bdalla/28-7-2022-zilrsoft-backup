<?php

namespace App\Models;

use App\Models\Traits\PostgresTimestamp;
use App\Scopes\OrganizationScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class BaseAuthModel extends Authenticatable
{
    use Notifiable, HasRoles, PostgresTimestamp;

    protected static function boot()
    {
        parent::boot();
        if (Auth::check()) {
            static::addGlobalScope(new OrganizationScope((int)Auth::user()->getOriginal("organization_id")));
        }
    }
}
