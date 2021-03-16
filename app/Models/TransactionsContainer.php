<?php

namespace App\Models;

use App\Models\Traits\AccountingPeriodTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed id
 * @property mixed created_at
 * @property mixed updated_at
 * @method static create(array $array)
 */
class TransactionsContainer extends BaseModel
{
    protected $guarded = [];
    use SoftDeletes;
    use AccountingPeriodTrait;

    public function getTotalCreditAmountAttribute()
    {
        return $this->transactions()->where('type', 'credit')->sum('amount');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'container_id');
    }


    // new relationship

    public function getTotalDebitAmountAttribute()
    {
        return $this->transactions()->where('type', 'debit')->sum('amount');
    }


    // old relationship

    public function entities()
    {
        return $this->hasMany(Transaction::class, 'container_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id');
    }
}
