<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends BaseModel
{
    //
    use SoftDeletes;

    protected $guarded = [];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id')->withoutGlobalScopes(['draft', 'manager']);
    }

    public function salesman(): BelongsTo
    {
        return $this->belongsTo(Manager::class, 'salesman_id');
    }

    public function serials(): HasMany
    {
        return $this->hasMany(ItemSerials::class, 'sale_invoice_id', 'invoice_id');
    }


}
