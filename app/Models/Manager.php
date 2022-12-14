<?php

namespace App\Models;

use App\ValueObjects\MoneyValueObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spinen\QuickBooks\HasQuickBooksToken;
use Spinen\QuickBooks\Token;

/**
 * @property mixed organization_id
 * @property mixed id
 * @property mixed accounts_closed_at
 * @property mixed created_at
 * @property mixed branch_id
 * @property mixed department_id
 * @property mixed organization
 * @property mixed creator_id
 * @property string phone_number
 * @property mixed name
 * @property mixed locale_name
 * @property mixed remaining_accounts_balance
 * @property mixed name_ar
 * @property mixed $quickBooksToken
 * @property mixed $quickbooks_class_id
 */
class Manager extends BaseAuthModel
{
    use \App\Traits\OrganizationTarget;
    use SoftDeletes;
    use Notifiable;
    use HasFactory;
    use HasQuickBooksToken;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'remaining_accounts_balance' => MoneyValueObject::class
    ];
    protected $appends = [
        'locale_name'
    ];


    public function getLocaleNameAttribute()
    {

        return $this->name_ar;
    }

    /**
     * @param $option
     * @return int
     */
    public function canDo($option): int
    {
        return $this->can($option) == true ? 1 : 0;
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(
            Organization::class,
            'organization_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'creator_id');
    }

    public function filters(): HasMany
    {
        return $this->hasMany(Filter::class, 'creator_id');
    }

    public function filters_values(): HasMany
    {
        return $this->hasMany(FilterValues::class, 'creator_id');
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'creator_id');
    }

    public function gateways(): BelongsToMany
    {
        return
            $this->belongsToMany(
                Account::class,
                "manager_gateways",
                'manager_id',
                'gateway_id'
            )
            ->withPivot('order_number as order_number')
            ->orderBy('order_number');
    }


    public function payments(): HasMany
    {
        return $this->hasMany(Voucher::class, 'creator_id');
    }

    public function resellerClosingAccounts(): HasMany
    {
        return $this->hasMany(ResellerClosingAccount::class, 'creator_id');
    }

    public function whatsappPhoneNumber(): ?string
    {
        return $this->getInternationalPhoneNumberAttribute();
    }

    public function getInternationalPhoneNumberAttribute(): ?string
    {
        return $this->phone_number;
    }

    public function ourSmsPhoneNumber(): string
    {
        return $this->getInternationalPhoneNumberAttribute();
    }

    public function receivesBroadcastNotificationsOn(): string
    {
        return 'manager_notification_channel_.' . $this->id;
    }

    /**
     * Have a quickBooksToken.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function quickBooksToken()
    {
        return $this->hasOne(Token::class, 'user_id');
    }
}
