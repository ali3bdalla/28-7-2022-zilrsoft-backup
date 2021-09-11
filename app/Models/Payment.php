<?php

namespace App\Models;

use App\Enums\VoucherTypeEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property HasOne user
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
        return $this->amount;
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

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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
