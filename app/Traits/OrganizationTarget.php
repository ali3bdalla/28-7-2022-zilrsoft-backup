<?php

namespace App\Traits;

use App\Models\Manager;
use App\Scopes\OrganizationScope;
use Illuminate\Support\Facades\Auth;

trait OrganizationTarget
{
    public static function bootOrganizationTarget()
    {

        if (Auth::guard('manager')->check()) {
            static::addGlobalScope(new OrganizationScope(Auth::user()->organization_id));
        } elseif (get_called_class() !== Manager::class) {
            static::addGlobalScope(new OrganizationScope());
        }
    }
}
