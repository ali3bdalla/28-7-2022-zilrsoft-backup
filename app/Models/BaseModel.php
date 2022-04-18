<?php

namespace App\Models;

use App\Scopes\DraftScope;
use App\Scopes\OrganizationScope;
use App\Scopes\PendingScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseModel extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DraftScope());
        static::addGlobalScope(new PendingScope());
    }

    public function getUpdatedAtAttribute($value): string
    {
        return Carbon::parse($value)->toDayDateTimeString();
    }

    public function getCreatedAtAttribute($value): string
    {
        return Carbon::parse($value)->toDayDateTimeString();
    }

    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar')) {
            return $this->getOriginal('ar_name');
        }

        return $this->getOriginal('name');
    }

    public function getLocaleDescriptionAttribute()
    {
        if (app()->isLocale('ar')) {
            return $this->getOriginal('ar_description');
        }

        return $this->getOriginal('description');
    }
}
