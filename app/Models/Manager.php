<?php

namespace App\Models;

use App\ValueObjects\MoneyValueObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Propaganistas\LaravelPhone\PhoneNumber;


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
 */
class Manager extends BaseAuthModel
{

    use SoftDeletes;
    use Notifiable;
    use HasFactory;

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
        return $this->hasMany(Payment::class, 'creator_id');
    }

    public function resellerClosingAccounts(): HasMany
    {
        return $this->hasMany(ResellerClosingAccount::class, 'creator_id');
    }

    public function whatsappPhoneNumber(): string
    {
        return $this->getInternationalPhoneNumberAttribute();
    }

    public function getInternationalPhoneNumberAttribute(): ?string
    {
//        $this->phone_number
        return "249966324018";
//        return (string)PhoneNumber::make($this->phone_number)->ofCountry($this->getOriginal("country_code", 'SA'));
    }

    public function ourSmsPhoneNumber(): string
    {
        return $this->getInternationalPhoneNumberAttribute();
    }

}
