<?php

namespace App\Models;

use App\Enums\TransactionDto;
use App\Models\Traits\AnnuallyScoped;
use Database\Factories\EntryFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * @property mixed id
 * @property mixed created_at
 * @property mixed updated_at
 * @property mixed amount
 * @property mixed organization_id
 * @property mixed creator_id
 * @property mixed is_pending
 * @method static create(array $array)
 */
class Entry extends BaseModel
{
    use \App\Traits\OrganizationTarget;
    protected $table = 'transactions_containers';
    use AnnuallyScoped;
    protected $guarded = [];
    use SoftDeletes;


    protected static function newFactory(): EntryFactory
    {
        return new EntryFactory();
    }

    public function addTransactions(Collection $transactions): Collection
    {
        return $transactions->map(function (TransactionDto $transactionDto) {
            return $this->transactions()->create([
                'creator_id' => Auth::id(),
                'organization_id' => $this->organization_id,
                'amount' => $transactionDto->getAmount(),
                'type' => $transactionDto->getType(),
                'is_pending' => $this->is_pending,
                'account_id' => $transactionDto->getAccountId(),
                'invoice_id' => $transactionDto->getInvoiceId(),
                'user_id' => $transactionDto->getUserId(),
                'item_id' => $transactionDto->getItemId(),
                'is_manual' => $transactionDto->isManual()
            ]);
        });
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(EntryTransaction::class, 'container_id');
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
        return $this->hasMany(EntryTransaction::class, 'container_id');
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
