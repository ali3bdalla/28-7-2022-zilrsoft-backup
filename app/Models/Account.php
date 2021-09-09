<?php

namespace App\Models;

use App\Events\Models\Account\AccountCreated;
use App\Events\Models\Account\AccountDeleted;
use App\Events\Models\Account\AccountUpdated;
use App\Models\Traits\AccountBalanceTrait;
use App\Models\Traits\NestingTrait;
use Closure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(array $array)
 * @method static whereNotIn(string $string, string[] $array)
 * @method static each(Closure $param)
 * @property mixed parent
 * @property mixed transactions
 * @property mixed locale_name
 * @property mixed type
 * @property mixed ar_name
 * @property mixed name
 * @property mixed total_debit_amount
 * @property mixed total_credit_amount
 * @property mixed id
 * @property mixed slug
 */
class Account extends BaseModel
{

    use SoftDeletes;
    use  NestingTrait;
    use AccountBalanceTrait;
    use HasFactory;

    protected $guarded = [];
    protected $appends = [
        'locale_name',
        'current_amount',
        'label',
        'is_expanded',
        'balance'
    ];
    protected $casts = [
        'is_gateway' => 'boolean',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => AccountCreated::class,
        'updated' => AccountUpdated::class,
        'deleted' => AccountDeleted::class,
    ];

    public function snapshots(): HasMany
    {
        return $this->hasMany(AccountSnapshot::class, 'account_id');
    }


    public function getSerialArrayAttribute($value)
    {
        return str_split($value);
    }

    public static function getSystemAccount($slug = "")
    {
        return static::where([
                ['is_system_account', true],
                ['slug', $slug],
            ]
        )->first();
    }


    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'account_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo($this, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany($this, 'parent_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }


    public function getIsExpandedAttribute(): bool
    {
        return false;
    }

    public function getLabelAttribute()
    {
        return $this->locale_name;
    }

    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar')) {
            return $this->ar_name;
        } else {
            return $this->name;
        }
    }

    public function getBalanceAttribute(): float
    {
        return $this->getSingleAccountBalance();
    }

    public function getSingleAccountBalance(): float
    {
        if ($this->_isCredit()) {
            return ((float)$this->total_credit_amount - (float)$this->total_debit_amount);
        }
        return ((float)$this->total_debit_amount - (float)$this->total_credit_amount);
    }


    public function _isCredit(): bool
    {
        return $this->type == 'credit';
    }

    public function getCurrentAmountAttribute()
    {

        $balance = $this->yearlyNestedAccountBalance();
        if (abs($balance) < 1)
            return 0;

        return $balance;
    }

    public function _isDebit(): bool
    {
        return $this->type == 'debit';
    }
}
