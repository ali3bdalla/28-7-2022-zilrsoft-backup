<?php

namespace App\Models;

use AliAbdalla\Tafqeet\Core\Tafqeet;
use App\Enums\InvoiceTypeEnum;
use App\Enums\VoucherTypeEnum;
use App\Models\Traits\AnnuallyScoped;
use App\Scopes\ActiveYearScope;
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
 * @property Invoice invoice
 */
class Voucher extends BaseModel
{
    use SoftDeletes;
    use AnnuallyScoped;
    protected $table = 'payments';
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
        return Tafqeet::arablic($this->amount);
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
        return $this->refund_payment_id == null && $this->refund_at == null && Auth::id() === $this->creator_id;
    }

    public function getUserAccount(): ?Account
    {
        $userAccount = $this->userAccount;
        if ($userAccount) return $userAccount;
        if ($this->invoice) {
            $invoice = $this->invoice;
            if ($invoice->invoice_type->equals(InvoiceTypeEnum::sale(), InvoiceTypeEnum::return_sale()))
                return Account::getSystemAccount("clients");
            else return Account::getSystemAccount("vendors");
        }
        return null;
    }
}
