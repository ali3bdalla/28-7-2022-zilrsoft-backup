<?php

namespace App\Models;

use App\Dto\InvoiceItemDto;
use App\Enums\AccountingTypeEnum;
use App\Enums\InvoiceTypeEnum;
use App\Scopes\DraftScope;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * @property mixed organization_id
 * @property mixed creator_id
 * @property mixed user_id
 * @property AccountingTypeEnum invoice_type
 * @property mixed net
 * @property mixed tax
 * @property mixed id
 * @property mixed branch_id
 * @property mixed department_id
 * @property mixed purchase
 * @property mixed total
 * @property mixed invoice_number
 * @property mixed items
 * @property mixed managed_by_id
 * @property mixed vendor_invoice_number
 * @property mixed is_draft
 * @property mixed created_at
 * @property mixed has_dropbox_snapshot
 * @property mixed dropbox_snapshot
 * @property mixed user
 * @property mixed creator
 * @property mixed is_online
 * @property mixed subtotal
 * @property mixed discount
 * @property mixed user_alice_name
 *
 * @method static create(array $array)
 */
class Invoice extends BaseModel
{
    use SoftDeletes;
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'printable_price' => 'boolean',
        'is_draft_converted' => 'boolean',
        'invoice_type' => InvoiceTypeEnum::class . ':nullable',
        'net' => MoneyValueObject::class,
        'total' => MoneyValueObject::class,
        'subtotal' => MoneyValueObject::class,
        'tax' => MoneyValueObject::class,
        'discount' => MoneyValueObject::class,
        'vts' => MoneyValueObject::class,
        'vtp' => MoneyValueObject::class,
    ];

    public static function getInvoiceByPublicIdHash($hash): Invoice
    {
        $publicIdElements = Invoice::getDecryptedPublicIdElements($hash);

        return Invoice::where([
            'created_at' => $publicIdElements->get('created_at', null),
            'id' => (int)$publicIdElements->get('id', 0),
        ])->firstOrFail();
    }

    public static function getDecryptedPublicIdElements($hash): Collection
    {
        $decryptedText = base64_decode($hash);

        return collect(json_decode($decryptedText));
    }

    public function getEncryptedPublicId(): string
    {
        return base64_encode(json_encode(['id' => $this->id, 'created_at' => $this->created_at]));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['manager', 'draft']);
    }

    public function getOrder()
    {
        return Order::where('invoice_id', $this->id)->first();
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Manager::class, 'managed_by_id');
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(InvoiceExpenses::class, 'invoice_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id')->withTrashed();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItems::class, 'invoice_id')->withoutGlobalScope(DraftScope::class);
    }

    public function serial_history(): HasOne
    {
        return $this->hasOne(SerialHistory::class, 'invoice_id');
    }

    public function child(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'parent_invoice_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'invoice_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }

    public function getUserTypeAttribute()
    {
        if (in_array($this->invoice_type, ['sale', 'return_sale', 'quotation'])) {
            return __('pages/invoice.client');
        }

        return __('pages/invoice.vendor');
    }

    public function getManagerTypeAttribute()
    {
        if (in_array($this->invoice_type, ['sale', 'return_sale', 'quotation'])) {
            return __('pages/invoice.salesman');
        }

        return __('pages/invoice.receiver');
    }

    public function getBackgroundAssetAttribute(): string
    {
        if ($this->is_draft) {
            if (app()->isLocale('ar')) {
                return asset('template/images/quotation-ar.png');
            }

            return asset('template/images/quotation.png');
        }

        if (app()->isLocale('ar')) {
            return asset('template/images/paid-ar.png');
        }

        return asset('template/images/paid.png');
    }

    public function getFinalUserNameAttribute()
    {
        if (in_array($this->invoice_type, ['sale', 'return_sale']) && null != $this->user_alice_name) {
            return $this->user_alice_name;
        }

        return $this->user->locale_name;
    }

    public function getHasDropboxSnapshotAttribute(): bool
    {
        return Storage::disk('dropbox')->exists($this->dropbox_snapshot);
    }

    public function getDropboxSnapshotUrlAttribute(): string
    {
        if ($this->has_dropbox_snapshot) {
            return Storage::disk('dropbox')->url($this->dropbox_snapshot);
        }

        return '';
    }

    public function addItems(Collection $items): Collection
    {
        return $items->each(function (InvoiceItemDto $invoiceItemDto) {
            $invoiceItemDto->setInvoice($this);
            $invoiceItem = InvoiceItems::factory()
                ->setDto($invoiceItemDto)
                ->create();
            $net = (float)$this->net + (float)$invoiceItem->net;
            $total = (float)$this->total + (float)$invoiceItem->total;
            $tax = (float)$this->tax + (float)$invoiceItem->tax;
            $discount = (float)$this->discount + (float)$invoiceItem->discount;
            $subtotal = (float)$this->subtotal + (float)$invoiceItem->subtotal;
            $this->update([
                'net' => $net,
                'total' => $total,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
            ]);
            return $invoiceItem;
        });
    }

    public function getUserNameAttribute()
    {
        return $this->user_alice_name ?? $this->user->locale_name;
    }
}
