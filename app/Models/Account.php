<?php

namespace App\Models;

use App\Enums\AccountingTypeEnum;
use App\Enums\AccountSlugEnum;
use App\Events\Models\Account\AccountCreated;
use App\Events\Models\Account\AccountDeleted;
use App\Events\Models\Account\AccountUpdated;
use App\Models\Traits\NestingTrait;
use App\Repository\AccountRepositoryContract;
use App\Repository\Eloquent\AccountRepository;
use App\Scopes\SortByScope;
use App\ValueObjects\GenericSortByValueObject;
use App\ValueObjects\TransactionSearchValueObject;
use Closure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static where(array $array)
 * @method static whereNotIn(string $string, string[] $array)
 * @method static each(Closure $param)
 * @property mixed parent
 * @property mixed transactions
 * @property mixed locale_name
 * @property AccountingTypeEnum type
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
    use NestingTrait;
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
        'type' => AccountingTypeEnum::class . ":nullable",
        'slug' => AccountSlugEnum::class . ":nullable"
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

    public function annualBalances(): MorphMany
    {
        return $this->morphMany(AnnualBalance::class, 'account');
    }
    public static function getSystemAccount($slug = "")
    {
        return static::where([
                ['is_system_account', true],
                ['slug', $slug],
            ]
        )->firstOrFail();
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SortByScope(new GenericSortByValueObject(new static(), 'serial', 'desc')));
    }

    public function snapshots(): HasMany
    {
        return $this->hasMany(AccountSnapshot::class, 'account_id');
    }

    public function getSerialArrayAttribute($value)
    {
        return str_split($value);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Voucher::class, 'account_id');
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
        return $this->hasMany(EntryTransaction::class, 'account_id');
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
        return (new AccountRepository())->getAccountBalance($this, new TransactionSearchValueObject());
    }


    public function isCredit(): bool
    {
        return $this->type->equals(AccountingTypeEnum::credit());
    }

    public function getCurrentAmountAttribute(): float
    {
        return $this->getSingleAccountBalance();
    }

    public function isDebit(): bool
    {
        return $this->type->equals(AccountingTypeEnum::debit());
    }

    public function managerGateways(): BelongsToMany
    {
        return
            $this->belongsToMany(
                Manager::class,
                'manager_gateways',
                'gateway_id',
                'manager_id'
            )
                ->withPivot('order_number as order_number')
                ->orderBy('order_number');
    }
}
