<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SerialHistory extends BaseModel
{
    protected $guarded = [];
    use \App\Traits\OrganizationTarget;

    public function serial(): BelongsTo
    {
        return $this->belongsTo(ItemSerials::class, 'serial_id');
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id')->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id')->withoutGlobalScopes(['manager', 'accountingPeriod']);
    }
    //
}
