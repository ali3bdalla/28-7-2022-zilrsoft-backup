<?php

namespace App\Traits;

use App\Models\Manager;
use App\Models\Organization;
use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
