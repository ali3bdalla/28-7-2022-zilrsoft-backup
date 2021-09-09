<?php

namespace App\Models;

use AliAbdalla\Tafqeet\Core\Tafqeet;
use App\Enums\VoucherTypeEnum;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed user
 * @property mixed payment_type
 * @property mixed amount
 */
class Payment extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'type' => VoucherTypeEnum::class . ':nullable'
    ];
    protected $guarded = [];

    protected $appends = [
        'amount_ar_words',
    ];

    public function getAmountArWordsAttribute()
    {
        return Tafqeet::arablic($this->amount);
    }

    /**
     * @return mixed
     */
    public function getSteakholderNameAttribute()
    {
        return $this->user->locale_name;
    }

    public function getSteakholderTypeAttribute()
    {
        if (in_array($this->payment_type, ['receipt'])) {
            return __('pages/vouchers.client');
        }

        return __('pages/vouchers.vendor');
    }

    public function getSteakholderPhoneNumberAttribute()
    {
        return $this->user->phone_number;
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id')->withTrashed();
    }

}
