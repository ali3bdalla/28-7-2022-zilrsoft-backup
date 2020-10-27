<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Traits\HasRoles;

class BaseAuthModel extends Authenticatable
{
    use Notifiable, HasRoles;

    protected static function boot()
    {
        parent::boot();
        $table = (new static )->getTable();
        if (auth()->guard('manager')->check() || auth()->user()) {
            if (Schema::hasColumn($table, 'organization_id')) {
                static::addGlobalScope(
                    'organization', function (Builder $builder) use ($table) {
                        $builder->where("{$table}.organization_id", auth()->user()->organization_id);
                    }
                );
            }

        }
    }
}
