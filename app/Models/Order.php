<?php

namespace App\Models;

use App\Scopes\DraftScope;
use App\Scopes\OrganizationScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @property mixed shipping_method_id
 * @property mixed shipping_address_id
 * @property mixed draft_id
 * @property mixed net
 * @property mixed|string status
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


    public function activities(): HasMany
    {
        return $this->hasMany(OrderActivity::class, 'order_id');
    }

    public function itemsQtyHolders(): HasMany
    {
        return $this->hasMany(OrderItemQtyHolder::class, 'order_id');
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_address_id');
    }

    public function shippable(): MorphTo
    {
        return $this->morphTo('shippable');

    }

    public function generatePayOrderUrl()
    {
        return file_get_contents('http://tinyurl.com/api-create.php?url=' . url('/web/orders/' . $this->id . '/confirm_payment?code=' . $this->order_secret_code));
    }

    public function generateCancelOrderUrl()
    {
        return file_get_contents('http://tinyurl.com/api-create.php?url=' . url('/web/orders/' . $this->id . '/cancel?code=' . $this->order_secret_code));
    }


    public function paymentDetail(): HasOne
    {
        return $this->hasOne(OrderPaymentDetail::class, 'order_id');
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id')->withoutGlobalScopes([OrganizationScope::class, DraftScope::class]);
    }

    public function deliveryMan(): BelongsTo
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }

    public function draftInvoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'draft_id')->withoutGlobalScopes([OrganizationScope::class, DraftScope::class]);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id')->withoutGlobalScopes([OrganizationScope::class, DraftScope::class]);

    }


}
