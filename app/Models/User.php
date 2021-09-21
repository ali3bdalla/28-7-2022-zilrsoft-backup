<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Propaganistas\LaravelPhone\PhoneNumber;

/**
 * @property int id
 * @property float balance
 * @property float vendor_balance
 * @property bool is_system_user
 * @property string country_code
 * @property string phone_number
 * @property bool is_client
 * @property string name_ar
 * @property bool is_manager
 * @property bool is_vendor
 * @property string international_phone_number
 * @property integer verification_code
 * @property mixed name
 * @property mixed creator_id
 * @property mixed organization_id
 * @method static where(array $array)
 * @method static find(mixed $user_id)
 */
class User extends BaseAuthModel
{

    use SoftDeletes;
    use Notifiable;


    protected $guarded = [];


    protected $appends = [
        'locale_name',
    ];
    protected $casts = [
        'is_vendor' => 'boolean',
        'is_client' => 'boolean',
        'is_supplier' => 'boolean',
        'can_make_credit' => 'boolean',
        'is_supervisor' => 'boolean',
        'is_manager' => 'boolean',
        'verification_code' => 'integer'
    ];

    protected $hidden = [
        'password'
    ];


    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function getYearlyBalance(Account $account)
    {
        $creditAmount = $account->transactions()->where(
            [
                ['type', 'credit'],
                ['user_id', $this->id]
            ]
        )->sum('amount');

        $debitAmount = $account->transactions()->where(
            [
                ['type', 'debit'],
                ['user_id', $this->id]
            ]
        )->sum('amount');

        if ($account->type == 'credit')
            return $creditAmount - $debitAmount;

        return $debitAmount - $creditAmount;
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'user_id');
    }

    public function getNameAttribute($value)
    {
        return $value;
    }

    public function getLocaleNameAttribute(): string
    {
        return $this->name_ar ?? $this->name;
    }


    public function shippingAddresses(): HasMany
    {
        return $this->hasMany(ShippingAddress::class, 'user_id');
    }

    public function details(): HasOne
    {
        return $this->hasOne(UserDetails::class, 'user_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id')->withTrashed();
    }

    public function manager(): HasOne
    {
        return $this->hasOne(Manager::class, 'user_id');
    }

    public function gateways(): HasMany
    {
        return $this->hasMany(UserGateways::class, 'user_id');
    }

    public function whatsappPhoneNumber(): string
    {
        return $this->getInternationalPhoneNumberAttribute();
    }

    public function getInternationalPhoneNumberAttribute(): string
    {
        return (string)PhoneNumber::make($this->phone_number)->ofCountry($this->getOriginal("country_code", 'SA'));
    }

    public function ourSmsPhoneNumber(): string
    {
        return $this->getInternationalPhoneNumberAttribute();
    }

    public function markPhoneNumberAsVerified()
    {
        $this->update([
            'phone_number_verified_at' => now(),
            'verification_code' => null
        ]);
    }

}
