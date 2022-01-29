<?php

namespace App\Traits;

use App\Scopes\OrganizationScope;
use Illuminate\Support\Facades\Auth;

trait OrganizationTarget
{
    public static function bootOrganizationTarget()
    {
        if (Auth::guard('manager')->check()) {
            static::addGlobalScope(new OrganizationScope(Auth::user()->organization_id));
        } else {
            static::addGlobalScope(new OrganizationScope());
        }
    }
}
