<?php

namespace App\Models;

use App\Relationships\InvoiceItemRelationships;
use App\Traits\OrmNumbersTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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
 * @method static findOrFail($id)
 */
class InvoiceItems extends BaseModel
{
    use InvoiceItemRelationships;
    use OrmNumbersTrait;

    protected $guarded = [];

    protected $appends = [
        'description',
    ];

    protected $casts = [
        'tax' => 'float',
        'total' => 'float',
        'discount' => 'float',
        'net' => 'float',
        'price' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();
        if (auth()->check()) {

            static::addGlobalScope('pendingItemsScope', function (Builder $builder) {
                $builder->where('is_pending', false);
            });
        }
    }

    public function fixPriceIssue($price)
    {
        $this->update([
            'price' => $price,
        ]);

        return $this->fresh();
    }

    public function isBelongToKit()
    {
        return $this->belong_to_kit;
    }

    public function scopeBelongToKit($query, InvoiceItems $oldDbKit)
    {
        return $query->where([
            ['belong_to_kit', true],
            ['parent_kit_id', $oldDbKit->id],
        ]);
    }

    public function getRQtyAttribute($value)
    {
        return $value;

        if ($this->invoice_type == 'sale') {
            return $this->item->serials()->where([
                ['sale_invoice_id', $this->invoice_id],
                ['current_status', 'saled'],
            ])->count();
        }

        return $this->item->serials()
            ->where('purchase_invoice_id', $this->invoice_id)
            ->whereIn(
                'current_status', ['available', 'purchase', 'r_sale']
            )->count();

    }

    public function getPrintableTaxAttribute()
    {

        if (in_array($this->invoice_type, ['sale', 'r_sale', 'quotation'])) {

            if (!$this->item->is_has_vts) {
                if ($this->item->vts_for_print > 0) {
                    return ($this->subtotal * $this->item->vts_for_print) / 100;
                } else {
                    return $this->tax;
                }
            } else {
                return $this->tax;
            }
        } else {
            if (!$this->item->is_has_vtp) {
                if ($this->item->vtp_for_print > 0) {
                    return ($this->subtotal * $this->item->vtp_for_print) / 100;
                } else {
                    return $this->tax;
                }
            } else {
                return $this->tax;
            }
        }
    }

    public function getPrintableNetAttribute()
    {

        return $this->roundOnLessThan1Cent($this->subtotal + $this->printable_tax);
    }

    public function addToReturnedQty($new_returned_qty)
    {
        return $this->update([
            'r_qty' => DB::raw("r_qty + $new_returned_qty"),
        ]);
    }

    public function updateStock()
    {

        $cost = $this->item->cost;
        $current_stock = $cost * $this->item->available_qty;
        $result = [];
        $result['final_stock_cost'] = $cost;
        if (in_array($this->invoice->invoice_type, ['purchase', 'beginning_inventory'])) {
            $result = $this->item->handlePurchaseHistory($this, $current_stock, $this->item->available_qty);
        } else if ($this->invoice->invoice_type == 'sale') {
            $result = $this->item->handleSaleHistory($this, $cost, $current_stock, $this->item->available_qty);
        } else if ($this->invoice->invoice_type == 'r_sale') {
            $result = $this->item->handleReturnSaleHistory($this, $current_stock, $cost, $this->item->available_qty);
        } else if ($this->invoice->invoice_type == 'r_purchase') {
            $result = $this->item->handleReturnPurchaseHistory($this, $cost, $current_stock, $this->item->available_qty);
        }

        $this->item->update([
            'cost' => $result['final_stock_cost'],
        ]);
    }

    public function getDescriptionAttribute()
    {
        $description = '';
        if (app()->isLocale('ar')) {
            if ($this->invoice_type == 'purchase') {
                $description = ' شراء';
            }

            if ($this->invoice_type == 'sale') {
                $description = 'بيع';
            }

            if ($this->invoice_type == 'beginning_inventory') {
                $description = 'اول مدة';
            }

            if ($this->invoice_type == 'r_sale') {
                $description = 'مرتجع بيع';
            }

            if ($this->invoice_type == 'r_purchase') {
                $description = 'مرتجع شراء';
            }

        } else {
            if ($this->invoice_type == 'sale') {
                $description = 'sale';
            }

            if ($this->invoice_type == 'purchase') {
                $description = 'purchase';
            }

            if ($this->invoice_type == 'beginning_inventory') {
                $description = 'beginning inventory';
            }

            if ($this->invoice_type == 'r_sale') {
                $description = 'return sale';
            }

            if ($this->invoice_type == 'r_purchase') {
                $description = 'return purchase';
            }

        }

        return $description;
    }

    public function getUrlsAttribute()
    {
        $urls = ['invoice_url' => '', 'invoice_title' => ''];
        if ($this->invoice) {
            if (in_array($this->invoice_type, ['sale', 'r_sale'])) {
                $urls['invoice_url'] = route('accounting.sales.show', $this->invoice->id);
            } else {
                $urls['invoice_url'] = route('accounting.purchases.show', $this->invoice->id);
            }

            $urls['invoice_title'] = $this->invoice->title;
        }

        return $urls;
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

    public function getAccountingDepetAttribute()
    {
        if (!in_array($this->invoice_type, ['sale', 'r_purchase'])) {
            return $this->cost * $this->qty;
        }

        return 0;
    }

    public function getAccountingCreditAttribute()
    {
        if (in_array($this->invoice_type, ['sale', 'r_purchase'])) {
            return $this->cost * $this->qty;
        }

        return 0;
    }

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
			
// 			if ($this->invoiceItem->invoice_type == 'r_purchase')
// 				$this->returnPurchase();
			
// 			if ($this->invoiceItem->invoice_type == 'r_sale')
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