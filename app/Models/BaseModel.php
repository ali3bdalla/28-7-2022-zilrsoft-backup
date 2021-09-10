<?php

namespace App\Models;

use App\Models\Traits\PostgresTimestamp;
use App\Scopes\DraftScope;
use App\Scopes\OrganizationScope;
use App\Scopes\PendingScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * @property string ar_description
 */
class BaseModel extends Model
{
    use PostgresTimestamp;

    protected static function boot()
    {
        parent::boot();
        if (Auth::check()) {
            static::addGlobalScope(new OrganizationScope(Auth::user()->organization_id));
        } else {
            static::addGlobalScope(new OrganizationScope(1));
        }
        static::addGlobalScope(new DraftScope());
        static::addGlobalScope(new PendingScope());
    }

    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar')) {
            return $this->ar_name;
        }

        return $this->name;
    }

    public function getLocaleDescriptionAttribute()
    {
        if (app()->isLocale('ar')) {
            return $this->ar_description;
        }

        return $this->description;
    }
}
