<?php

namespace App\Models;

use App\Enums\AccountingTypeEnum;
use App\Events\Models\Transaction\TransactionCreated;
use App\Models\Traits\AnnuallyScoped;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed account
 * @property mixed amount
 * @property mixed created_at
 * @property mixed type
 * @property mixed user_id
 * @property mixed account_id
 * @property mixed item_id
 * @method static where(array $array)
 */
class EntryTransaction extends BaseModel
{
    use  SoftDeletes;
    use  HasFactory;
    use AnnuallyScoped;
    use \App\Traits\OrganizationTarget;
    protected $table = "transactions";
    protected $with = ["account", 'invoice', 'user', 'item'];
    protected $guarded = [];
    protected $casts = [
        'amount' => MoneyValueObject::class,
        'total_debit_amount' => MoneyValueObject::class,
        'total_credit_amount' => MoneyValueObject::class,
        'type' => AccountingTypeEnum::class . ":nullable"
    ];
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => TransactionCreated::class,
    ];


    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }


    public function getAccountNameAttribute()
    {
        $account = $this->account;
        if (($account->slug == 'vendors' || $account->slug == 'clients') && $this->user_id) {
            $user = User::find($this->user_id);
            if ($user) {
                return $user->name;
            }
        }
        if (($account->slug == 'stock') && $this->item_id) {
            $item = Item::find($this->item_id);
            if ($item) {
                return $item->locale_name;
            }
        }

        return $this->account->locale_name;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }


    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id')->withoutGlobalScopes(['manager', 'accountingPeriod']);
    }

    public function container(): BelongsTo
    {
        return $this->belongsTo(Entry::class, 'container_id');
    }


    public function getDescriptionAttribute($value)
    {
        if ($value == 'close_account') {
            return '?????????? ??????????????';
        }

        if ($value == 'transfer_amount') {
            return '??????????';
        }
        if ($value == 'vendor_balance') {
            return '???????? ????????????';
        }

        return $value;
    }


    public function isCredit(): bool
    {
        return $this->type->equals(AccountingTypeEnum::credit());
    }

    public function isDebit(): bool
    {
        return $this->type->equals(AccountingTypeEnum::debit());
    }
}
