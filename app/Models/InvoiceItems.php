<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

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


    public function getRQtyAttribute()
    {
        return $this->returned_qty;
    }


    protected $casts = [
        'tax' => 'float',
        'total' => 'float',
        'discount' => 'float',
        'net' => 'float',
        'price' => 'float',
        'is_draft' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        if (auth()->check()) {
            static::addGlobalScope('draft', function (Builder $builder) {
                $builder->where('is_draft', false);
            });
        }
    }


    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    
    public function scopeKitItems($query , $kitId = 0)
    {
        return $query ->where('parent_kit_id',$kitId);
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }


    public function getDescriptionAttribute()
    {
        return $this->invoice_type;
    }

    public function getInvoiceUrlAttribute()
    {
        if (in_array($this->invoice_type, ['purchase', 'return_purchase'])) return "/purchases/{$this->invoice_id}";
        else return "/sales/{$this->invoice_id}";
    }

    public function getInvoiceNumberAttribute()
    {
        $invoice = $this->invoice()->withoutGlobalScope('draft')->withoutGlobalScope('manager')->first();
        if($invoice)
            return $invoice->invoice_number;

        return "";
    }

}

