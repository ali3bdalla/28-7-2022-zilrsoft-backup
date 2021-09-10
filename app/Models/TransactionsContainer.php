<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * @property mixed id
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed amount
 * @method static create(array $array)
 */
class TransactionsContainer extends BaseModel
{
    protected $guarded = [];
    use SoftDeletes;

    public static function createEntry(array $attributes = [], array $transactions = []): TransactionsContainer
    {
        $entry = static::create(array_merge($attributes, [
            'organization_id' => Auth::user()->organization_id,
            'creator_id' => Auth::id(),
            'amount' => 0
        ]));
        $entry->addTransactions($transactions);
        return $entry;
    }

    public function addTransactions($transactions = [])
    {
        $entryAmount = $this->amount;
        foreach ($transactions as $transaction) {
            $transaction = $this->transactions()->create(
                array_merge($transaction, [
                    'organization_id' => Auth::user()->organization_id,
                    'creator_id' => Auth::id()
                ])
            );
            if ($transaction->isDebit()) $entryAmount += (float)$transaction->amount;
        }

        $this->update([
            'amount' => $entryAmount
        ]);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'container_id');
    }

    public function getTotalCreditAmountAttribute()
    {
        return $this->transactions()->where('type', 'credit')->sum('amount');
    }

    public function getTotalDebitAmountAttribute()
    {
        return $this->transactions()->where('type', 'debit')->sum('amount');
    }

    public function entities(): HasMany
    {
        return $this->hasMany(Transaction::class, 'container_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id')->withTrashed();
    }
}
