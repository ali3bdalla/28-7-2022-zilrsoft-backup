<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class BaseAuthModel extends  Authenticatable
{
    use Notifiable,HasRoles;

    protected static function boot()
    {
        parent::boot();
        if (auth()->check()) {
            // dd(auth()->check());
            static::addGlobalScope('organization', function (Builder $builder) {
                $builder->where('organization_id', auth()->user()->organization_id);
            });
        }
    }
}