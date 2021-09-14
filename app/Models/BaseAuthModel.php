<?php

namespace App\Models;

use App\Models\Traits\PostgresTimestamp;
use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class BaseAuthModel extends Authenticatable
{
    use Notifiable, HasRoles, PostgresTimestamp;
    use HasFactory;
    protected static function boot()
    {
        parent::boot();
        if (Auth::check()) {
            static::addGlobalScope(new OrganizationScope((int)Auth::user()->organization_id));
        }
    }
}
