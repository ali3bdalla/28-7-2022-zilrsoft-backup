<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed name
 * @property mixed ar_name
 */
class Expense extends BaseModel
{
    use SoftDeletes;

    protected $guarded = [];

    protected $appends = [
        'locale_name'
    ];


    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar'))
            return $this->ar_name;

        return $this->name;

    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id')->withTrashed();
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(InvoiceExpenses::class, 'expense_id');
    }
}
