<?php

namespace App\Models;

use App\Enums\VoucherTypeEnum;
use Database\Factories\VoucherFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * @property HasOne user
 * @property VoucherTypeEnum payment_type
 * @property mixed amount
 * @property mixed user_id
 * @property Account account
 * @property mixed description
 * @property mixed id
 * @property mixed refund_at
 * @property mixed creator_id
 * @property VoucherTypeEnum type
 * @property mixed refund_payment_id
 * @property Account userAccount
 */
class Payment extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'payment_type' => VoucherTypeEnum::class . ':nullable',
    ];
    protected $guarded = [];
    protected $appends = [
        'amount_ar_words',
    ];

    protected static function newFactory(): VoucherFactory
    {
        return new VoucherFactory();
    }

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
    public function userAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'user_account_id');
    }
    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id')->withTrashed();
    }

    public function markAsRefunded()
    {
        $this->update([
            'refund_at' => now()
        ]);
    }

    public function isRefundable(): bool
    {
        return $this->refund_payment_id == null  && $this->refund_at == null && Auth::id() === $this->creator_id;
    }
}
