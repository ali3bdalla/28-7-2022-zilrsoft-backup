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


    // public function fixPriceIssue($price)
    // {
    //     $this->update([
    //         'price' => $price,
    //     ]);

    //     return $this->fresh();
    // }

    // public function isBelongToKit()
    // {
    //     return $this->belong_to_kit;
    // }

    // public function scopeBelongToKit($query, InvoiceItems $oldDbKit)
    // {
    //     return $query->where([
    //         ['belong_to_kit', true],
    //         ['parent_kit_id', $oldDbKit->id],
    //     ]);
    // }

    // public function getRQtyAttribute($value)
    // {
    //     return $value;

    //     if ($this->invoice_type == 'sale') {
    //         return $this->item->serials()->where([
    //             ['sale_invoice_id', $this->invoice_id],
    //             ['current_status', 'saled'],
    //         ])->count();
    //     }

    //     return $this->item->serials()
    //         ->where('purchase_invoice_id', $this->invoice_id)
    //         ->whereIn(
    //             'current_status', ['available', 'purchase', 'return_sale']
    //         )->count();

    // }

    // public function getPrintableTaxAttribute()
    // {

    //     if (in_array($this->invoice_type, ['sale', 'return_sale', 'quotation'])) {

    //         if (!$this->item->is_has_vts) {
    //             if ($this->item->vts_for_print > 0) {
    //                 return ($this->subtotal * $this->item->vts_for_print) / 100;
    //             } else {
    //                 return $this->tax;
    //             }
    //         } else {
    //             return $this->tax;
    //         }
    //     } else {
    //         if (!$this->item->is_has_vtp) {
    //             if ($this->item->vtp_for_print > 0) {
    //                 return ($this->subtotal * $this->item->vtp_for_print) / 100;
    //             } else {
    //                 return $this->tax;
    //             }
    //         } else {
    //             return $this->tax;
    //         }
    //     }
    // }

    // public function getPrintableNetAttribute()
    // {

    //     return $this->roundOnLessThan1Cent($this->subtotal + $this->printable_tax);
    // }

    // public function addToReturnedQty($new_returned_qty)
    // {
    //     return $this->update([
    //         'r_qty' => DB::raw("r_qty + $new_returned_qty"),
    //     ]);
    // }

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
        return $this->invoice()->withoutGlobalScope('draft')->first()->invoice_number;
    }


    // public function getPriceAttribute($value)
    // {

    //     return $this->moneyFormatter($value);
    // }

    // public function getTotalAttribute($value)
    // {
    //     return $this->moneyFormatter($value);
    // }

    // public function getDiscountAttribute($value)
    // {

    //     return $this->moneyFormatter($value);
    // }

    // public function getTaxAttribute($value)
    // {

    //     return $this->moneyFormatter($value);
    // }

    // public function getNetAttribute($value)
    // {
    //     return $this->moneyFormatter($value);
    // }

    // public function getSubtotalAttribute($value)
    // {

    //     return $this->moneyFormatter($value);
    // }

    // public function getAccountingDepetAttribute()
    // {
    //     if (!in_array($this->invoice_type, ['sale', 'return_purchase'])) {
    //         return $this->cost * $this->qty;
    //     }

    //     return 0;
    // }

    // public function getAccountingCreditAttribute()
    // {
    //     if (in_array($this->invoice_type, ['sale', 'return_purchase'])) {
    //         return $this->cost * $this->qty;
    //     }

    //     return 0;
    // }

}


// private $invoiceItem = null;
// 		private $item_statistics = null;


// 		private $profit = 0;
// 		public function __construct(InvoiceItems $invoiceItem)
// 		{
// 			$this->invoiceItem = $invoiceItem;
// 			$this->getItemStatisticsRow();
// 			if ($this->invoiceItem->invoice_type == 'sale')
// 				$this->sales();

// 			if ($this->invoiceItem->invoice_type == 'purchase')
// 				$this->purchase();

// 			if ($this->invoiceItem->invoice_type == 'return_purchase')
// 				$this->returnPurchase();

// 			if ($this->invoiceItem->invoice_type == 'return_sale')
// 				$this->returnSales();

// 		}

// 		private function getItemStatisticsRow()
// 		{
// 			$this->item_statistics = $this->invoiceItem->item->statistics;
// 			if (!$this->item_statistics)
// 				$this->item_statistics = $this->invoiceItem->item->statistics()->create();

// 			$this->profit = $this->invoiceItem->total - ($this->invoiceItem->cost * $this->invoiceItem->qty);

// 		}

// 		private function sales()
// 		{


// 			$this->item_statistics->update([
// 				'sales_count' => DB::raw("sales_count + 1"),
// 				'profits' => DB::raw("profits + $this->profit"),
// 			]);


// 		}

// 		private function returnSales()
// 		{

// 			$this->item_statistics->update([
// 				'return_sales_count' => DB::raw("return_sales_count + 1"),
// 				'profits' => DB::raw("profits - $this->profit"),
// 			]);


// 		}


// 		private function purchase()
// 		{

// 			$this->item_statistics->update([
// 				'purchase_count' => DB::raw("purchase_count + 1")
// 			]);


// 		}


// 		private function returnPurchase()
// 		{

// 			$this->item_statistics->update([
// 				'return_purchase_count' => DB::raw("return_purchase_count + 1")
// 			]);


// 		}