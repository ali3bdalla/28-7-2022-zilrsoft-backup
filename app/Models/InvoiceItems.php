<?php

namespace App\Models;

use App\Scopes\DraftScope;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed item
 * @property mixed cost
 * @property mixed qty
 * @property mixed invoice_id
 * @property mixed user_id
 * @property mixed discount
 * @property mixed price
 * @property mixed invoice
 * @property mixed r_qty
 * @property mixed data
 * @property mixed total
 * @property mixed items
 * @property mixed belong_to_kit
 * @property mixed id
 * @property mixed subtotal
 * @property mixed invoice_type
 * @property mixed tax
 * @property mixed organization_id
 * @property mixed creator_id
 * @property mixed returned_qty
 * @property mixed item_id
 * @property mixed net
 * @method static findOrFail($id)
 */
class InvoiceItems extends BaseModel
{

    protected $guarded = [];

    protected $appends = [
        'description',
        'invoice_url',
        'invoice_number',
        'r_qty'
    ];
    protected $casts = [
        'is_draft' => 'boolean',
        'net' => MoneyValueObject::class,
        'total' => MoneyValueObject::class,
        'subtotal' => MoneyValueObject::class,
        'tax' => MoneyValueObject::class,
        'discount' => MoneyValueObject::class,
        'price' => MoneyValueObject::class,
    ];


    public function getRQtyAttribute()
    {
        return $this->returned_qty;
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function scopeKitItems($query, $kitId = 0)
    {
        return $query->where('parent_kit_id', $kitId);
    }

    public function getInvoiceItemSerials()
    {
        return $this->item->serials()
            ->where(
                [
                    ["sale_id", $this->invoice_id],
                    ["item_id", $this->item->id],
                ]
            )
            ->orWhere([["return_sale_id", $this->invoice_id], ["item_id", $this->item->id]])
            ->orWhere([["return_purchase_id", $this->invoice_id], ["item_id", $this->item->id]])
            ->orWhere([["purchase_id", $this->invoice_id], ["item_id", $this->item->id]])
            ->get();


    }

    public function orderQtyHolders(): HasMany
    {
        return $this->hasMany(OrderItemQtyHolder::class, 'item_id');
    }


    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id')->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDescriptionAttribute()
    {
        return $this->invoice_type;
    }

    public function getInvoiceUrlAttribute(): string
    {
        if (in_array($this->invoice_type, ['purchase', 'return_purchase'])) return "/purchases/{$this->invoice_id}";
        else return "/sales/{$this->invoice_id}";
    }

    public function getInvoiceNumberAttribute()
    {
        $invoice = $this->invoice()->withoutGlobalScope(DraftScope::class)->first();
        if ($invoice)
            return $invoice->getOriginal("invoice_number");
        return "";
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id')->withoutGlobalScopes(['manager', 'accountingPeriod']);
    }

}

