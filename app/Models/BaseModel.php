<?php

namespace App\Models;

use App\Models\Traits\PostgresTimestamp;
use App\Scopes\DraftScope;
use App\Scopes\OrganizationScope;
use App\Scopes\PendingScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseModel extends Model
{
    use PostgresTimestamp;
    use HasFactory;
    protected static function boot()
    {
        parent::boot();
        if (Auth::guard('manager')->check()) {
            $organizationId = Auth::user()->organization_id;
            static::addGlobalScope(new OrganizationScope($organizationId));
        } else {
            static::addGlobalScope(new OrganizationScope());
        }
        static::addGlobalScope(new DraftScope());
        static::addGlobalScope(new PendingScope());
    }

    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar')) return $this->getOriginal("ar_name");
        return $this->getOriginal("name");
    }

    public function getLocaleDescriptionAttribute()
    {
        if (app()->isLocale('ar')) {
            return $this->getOriginal("ar_description");
        }

        return $this->getOriginal("description");
    }
}
