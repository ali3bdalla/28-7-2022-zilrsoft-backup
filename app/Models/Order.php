<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Scopes\DraftScope;
use App\ValueObjects\MoneyValueObject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @property mixed shipping_method_id
 * @property mixed shipping_address_id
 * @property mixed draft_id
 * @property float net
 * @property OrderStatusEnum status
 * @property mixed user_id
 * @property mixed auto_cancel_at
 * @property false|mixed is_should_pay_notified
 * @property false|mixed should_pay_last_notification_at
 * @property mixed itemsQtyHolders
 * @property Carbon|mixed cancel_order_code
 * @property mixed order_secret_code
 * @property mixed id
 * @property mixed|string shippable_type
 * @property int|mixed shippable_id
 * @property mixed shippable
 * @property mixed payment_method_id
 * @property mixed payment_method
 * @property float|int|mixed shipping_cost
 * @property int|mixed shipping_amount
 * @property float|int|mixed shipping_weight
 * @property mixed user
 * @property mixed delivered_at
 * @property mixed shipped_at
 * @property mixed shippingAddress
 * @property mixed shippingMethod
 * @property mixed invoice
 * @property mixed delivery_man_code
 * @property mixed deliveryMan
 * @property mixed tracking_number
 * @property mixed pdf_url
 * @property mixed pdf_path
 * @property mixed organization_id
 * @property mixed|string lang
 */
class Order extends BaseModel
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['pdf_url'];

    protected $casts = [
        'status' => OrderStatusEnum::class . ':nullable',
        'net' => MoneyValueObject::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function managedBy(): BelongsTo
    {
        return $this->belongsTo(Manager::class, 'managed_by_id');
    }

    public function getPdfUrlAttribute(): string
    {
        return Storage::url($this->pdf_path);
    }


    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_address_id');
    }


    public function generatePayOrderUrl()
    {
        return shortLink(url('/web/orders/' . $this->id . '/confirm_payment?code=' . $this->order_secret_code));
    }

    public function generateCancelOrderUrl()
    {
        return shortLink(url('/web/orders/' . $this->id . '/cancel?code=' . $this->order_secret_code));
    }

    public function paymentDetail(): HasOne
    {
        return $this->hasOne(OrderPaymentDetail::class, 'order_id');
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id')->withoutGlobalScopes([DraftScope::class]);
    }

    public function deliveryMan(): BelongsTo
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }

    public function draftInvoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'draft_id')->withoutGlobalScopes([DraftScope::class]);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id')->withoutGlobalScopes([DraftScope::class]);
    }

    public function isPending(): bool
    {
        return $this->status->equals(OrderStatusEnum::pending());
    }

    public function markAsAutoCanceledNotified()
    {
        $this->update(
            [
                'is_should_pay_notified' => true
            ]
        );
    }

    public function markAsCanceled()
    {
        $this->update(
            [
                'status' => 'canceled'
            ]
        );
    }
}
