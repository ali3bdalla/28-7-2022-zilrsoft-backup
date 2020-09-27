<?php

namespace App\Models;

use App\Relationships\ItemRelationships;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed is_fixed_price
 * @property mixed price
 * @property mixed is_expense
 * @property mixed cost
 * @property mixed id
 * @property mixed vts
 * @property mixed available_qty
 * @property mixed data
 * @property mixed items
 * @property mixed vtp
 * @property mixed expense_vendor_id
 * @property mixed invoice_id
 * @property mixed total_credit_amount
 * @property mixed total_debit_amount
 * @property mixed returned_qty
 * @property mixed is_need_serial
 * @property mixed total_cost_amount
 * @property mixed total_stock_amount
 * @property mixed is_service
 * @method static findOrFail($id)
 * @method static InRandomOrder()
 * @method static find($input)
 */
class Item extends BaseModel
{
    // use KitAttributes;
    // use KitRelationships;
    // use WebItem;
    use SoftDeletes;

    protected $appends = [
        'locale_name',
        // 'warranty_title',
    ];
    protected $casts = [
        'id' => 'integer',
        'is_has_vts' => 'boolean',
        'is_has_vtp' => 'boolean',
        'is_fixed_price' => 'boolean',
        'is_kit' => 'boolean',
        'is_service' => 'boolean',
        'is_need_serial' => 'boolean',
        'available_qty' => 'integer',
        'price' => 'float',
        'price_with_tax' => 'float',
        'is_expense' => 'boolean',
    ];
    protected $guarded = [];


    public function items()
    {
        return $this->hasMany(KitItems::class, 'kit_id')->with('item');
    }


    public function data()
    {
        return $this->hasOne(KitData::class, 'kit_id');
    }

    public function scopeKits($query)
    {
        return $query->where('is_kit', true);
    }


    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function serials()
    {
        return $this->hasMany(ItemSerials::class, 'item_id');
    }

    public function pipeline()
    {
        return $this->hasMany(InvoiceItems::class, 'item_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function filters()
    {
        return $this->hasMany(ItemFilters::class, 'item_id');
    }

    public function creator()
    {
        return $this->belongsTo(Manager::class, 'creator_id');
    }

    // new

    // public function scopeExpense($query)
    // {
    //     return $query->where('is_expense', true);
    // }

    // public function scopeKit($query)
    // {
    //     return $query->where('is_kit', true);
    // }

    // public function scopeHasSerial($query)
    // {
    //     return $query->where('is_need_serial', true);
    // }

    // public function scopeNotKit($query)
    // {
    //     return $query->where('is_kit', false);
    // }

    // public function scopeNotExpense($query)
    // {
    //     return $query->where('is_expense', false);
    // }

    // public function scopeNotHasSerial($query)
    // {
    //     return $query->where('is_need_serial', false);
    // }

    // public function isQtyable()
    // {
    //     return $this->isCostableItem();
    // }

    // public function isCostableItem()
    // {
    //     return !$this->is_kit && !$this->is_service;
    // }

    // public function isExpense()
    // {
    //     return $this->is_expense;
    // }

    // public function getSalePrice($userPrice = 0)
    // {
    //     return $this->is_fixed_price ? $this->price : (float) $userPrice;
    // }

    // public function getPurchaseTax($subtotal = 0)
    // {
    //     return ((float) $subtotal * $this->vtp) / 100;
    // }

    // public function getSaleTax($subtotal = 0)
    // {
    //     return (float) $subtotal * $this->vts / 100;
    // }

    // public function getPurchaseTaxAsFloatValue()
    // {
    //     return (1 + $this->vtp / 100);
    // }

    // public function getSaleTaxAsFloatValue()
    // {
    //     return (1 + $this->vts / 100);
    // }

    // public function availableQty()
    // {
    //     return (int) $this->available_qty;
    // }

    // public function isNeedSerial()
    // {
    //     return $this->is_need_serial;
    // }

    // public function isKit()
    // {
    //     return $this->is_kit;
    // }

    // public function isService()
    // {
    //     return $this->is_service;
    // }

    // // old

    // public function getFormattedTitleAttribute()
    // {
    //     return $this->name;
    // }

    // public function getFormattedPriceAttribute()
    // {
    //     $price = $this->price_with_tax;
    //     if ($this->is_kit) {
    //         $price = $this->data->net;
    //     }
    //     return $price;
    // }

    // public function getWarrantyTitleAttribute()
    // {
    //     if (!empty($this->warranty)) {
    //         return $this->warranty->locale_name;
    //     }

    //     return "";
    // }

    // public function getSalesCountAttribute()
    // {

    //     if ($this->statistics) {
    //         return $this->statistics->sales_count;
    //     }

    //     return 0;
    // }

    // public function getLocaleWarranyAttribute()
    // {
    //     if ($this->warranty) {
    //         if (app()->isLocate('ar')) {
    //             return $this->warranty->ar_name;
    //         } else {
    //             return $this->warranty->name;
    //         }

    //     }

    //     return null;
    // }

    // public function getCreatedAtAttribute($value)
    // {
    //     return Carbon::parse($value)->toDateString();
    // }

    // public function getDataForReturn($qty, $fresh_invoice_item)
    // {

    //     $data['total'] = $fresh_invoice_item->price * $qty;
    //     $data['discount'] = $fresh_invoice_item->discount / $fresh_invoice_item->qty * $qty;
    //     $data['tax'] = $fresh_invoice_item->tax / $fresh_invoice_item->qty * $qty;

    //     $data['subtotal'] = $data['total'] - $data['discount'];
    //     $data['net'] = $data['subtotal'] + $data['tax'];
    //     $data['organization_id'] = $fresh_invoice_item->organization_id;
    //     $data['item_id'] = $this->id;
    //     $data['user_id'] = $fresh_invoice_item->user_id;
    //     $data['price'] = $fresh_invoice_item->price;
    //     $data['creator_id'] = auth()->user()->id;
    //     return $data;
    // }

    public function scopeLastFiveSearch($query, $search)
    {
        return $query
            ->where('barcode', 'LIKE', '%' . $search . '%')
            ->orWhere('name', 'LIKE', '%' . $search . '%')
            ->orWhere('ar_name', 'LIKE', '%' . $search . '%')
            ->with('items', 'data')
            ->take(5);
    }

    public function scopeItemBySerialSearch($query, $search)
    {
        $pserials = ItemSerials::where('serial', $search)->whereIn('current_status', ['available', 'return_sale'])->get();
        $serials = [];
        foreach ($pserials as $serial) {
            if (!empty($serial)) {
                $item = $serial->item;
                $item->has_init_serial = true;
                $item->init_serial = $serial->fresh();
                $serials[] = $item;
            }
        }

        return $serials;
    }

    // public function updateItemAvailableQty($option, $qty)
    // {
    //     if ($option == 'add') {
    //         $this->update([
    //             'available_qty' => DB::raw("available_qty + $qty "),
    //         ]);
    //         return true;
    //     } else {

    //         $this->update([
    //             'available_qty' => DB::raw("available_qty - $qty "),
    //         ]);
    //         return true;
    //     }
    // }

    // public function updateSalesPrice($price_with_tax)
    // {
    //     if (is_numeric($price_with_tax)) {
    //         $price_without_tax = $price_with_tax / $this->getItemVatSaleAsValue();
    //         $this->update([
    //             'price' => $price_without_tax,
    //             'price_with_tax' => $price_with_tax,
    //         ]);
    //     }
    // }

    // public function getItemVatSaleAsValue()
    // {
    //     return 1 + $this->vts / 100;
    // }

    // public function updateLastPurchasePrice($last_price)
    // {
    //     $this->update([
    //         'last_p_price' => $last_price,
    //     ]);
    // }

    // public function setSerialAs($status, $serials = [], $invoice = null)
    // {
    //     if ($status == 'return_sale') {
    //         $data = [
    //             'current_status' => $status,
    //             'return_sale_invoice_id' => $invoice->id,
    //         ];
    //         $user_id = $invoice->sale->client_id;
    //     } else {
    //         $data = [
    //             'current_status' => $status,
    //             'return_purchase_invoice_id' => $invoice->id,
    //         ];
    //         $user_id = $invoice->purchase->vendor_id;
    //     }
    //     $this->serials()->whereIn('id', collect($serials)->pluck('id')->toArray())->update($data);

    //     foreach ($serials as $serial) {
    //         $invoice->serial_history()->create([
    //             'event' => $status,
    //             'organization_id' => auth()->user()->organization_id,
    //             'creator_id' => auth()->user()->id,
    //             'serial_id' => $serial['id'],
    //             'user_id' => $user_id,
    //         ]);
    //     }

    // }

    // public function validatePurchaseData($data)
    // {

    //     return true;

    // }

    // public function checkIfItHasEnoughQtyForReturn($qty, $fresh_invoice_item, $sub_invoice)
    // {
    //     $total_returned = $qty + $fresh_invoice_item['r_qty'];
    //     if ($sub_invoice instanceof Purchase) {
    //         if ($qty > $this->available_qty) {
    //             return false;
    //         }
    //     }

    //     if ($total_returned > $fresh_invoice_item['qty']) {
    //         return false;
    //     }

    //     return true;
    // }

    // public function getListOfReturnedSerial($serials, $return_invoice)
    // {

    //     $result = [];
    //     foreach ($serials as $serial) {
    //         if ($serial['current_status'] == $return_invoice) {
    //             $fresh_serial = $this->serials()->where('id', $serial['id'])->first();
    //             if ($fresh_serial['current_status'] != $return_invoice) {
    //                 $result[] = $serial;
    //             }
    //         }
    //     }

    //     return $result;
    // }

    public function getLocaleNameAttribute()
    {
        return $this->ar_name;
    }

    // public static function higherSalesItemsQuery($take = 5)
    // {
    //     return self::where([
    //         ['is_service', false],
    //         ['is_kit', false],
    //     ])
    //         ->select('items.*')
    //         ->leftJoin('item_statistics', 'item_statistics.item_id', '=', 'items.id')
    //         ->orderBy('item_statistics.sales_count', 'desc')
    //         ->take($take)
    //         ->get();
    // }

    // public static function latestItemsQuery($take = 5)
    // {
    //     return self::where([
    //         ['is_service', false],
    //         ['is_kit', false],
    //     ])->latest()->take($take)->get();
    // }

    // public static function bannerItemQuery($take = 5)
    // {
    //     return self::where([
    //         ['is_service', false],
    //         ['is_kit', false],
    //     ])->first();
    // }

    // public static function formattedCollectionQuery($cell, $orderType = 'desc', $take = 5)
    // {
    //     return self::where([
    //         ['is_service', false],
    //         ['is_kit', false],
    //     ])->orderBy($cell, $orderType)->take($take)->get();
    // }

    // public static function formattedPackageCollectionQuery($cell = 'id', $orderType = 'desc', $take = 5)
    // {
    //     return self::where([
    //         ['is_service', false],
    //         ['is_kit', true],
    //     ])->orderBy($cell, $orderType)->take($take)->get();
    // }

    // /**
    //  * @param $history
    //  * @param $stock_value
    //  * @param $stock_qty
    //  *
    //  * @return mixed
    //  */
    // public function handlePurchaseHistory($history, $stock_value, $stock_qty)
    // {
    //     $result = $history;

    //     $result['current_move_stock_qty'] = $stock_qty + $history['qty'];
    //     $result['current_move_stock_total'] = $stock_value + $history['total'];

    //     if ($result['current_move_stock_qty'] > 0) {
    //         $result['current_move_stock_cost'] = $result['current_move_stock_total'] / $result['current_move_stock_qty'];
    //     }

    //     if ($history['is_service']) {
    //         $result['current_move_stock_cost'] = 0;
    //     }

    //     $result['final_stock_total'] = $result['current_move_stock_total'];
    //     $result['final_stock_cost'] = $result['current_move_stock_cost'];
    //     $result['final_stock_qty'] = $result['current_move_stock_qty'];

    //     if ($history['discount'] > 0) {
    //         $result = $this->handlePurchaseDiscountHistory($result);
    //     }

    //     $result = $this->handlePurchaseExpensesHistroy($result);

    //     return $result;
    // }

    // /**
    //  * @param $history
    //  *
    //  * @return mixed
    //  */
    // public function handlePurchaseDiscountHistory($history)
    // {

    //     $after_discount_stock_total = $history['current_move_stock_total'] - $history['discount'];

    //     if ($history['current_move_stock_qty'] > 0) {
    //         $cost = $after_discount_stock_total / $history['current_move_stock_qty'];
    //     } else {
    //         $cost = $history['current_move_stock_qty'];
    //     }

    //     $history['has_purchase_discount'] = true;
    //     $history['discount_data'] = [
    //         'discount_stock_total' => $after_discount_stock_total,
    //         'discount_stock_cost' => $cost,
    //     ];
    //     $history['total_cost'] = $cost * $history['qty'];

    //     $history['final_stock_total'] = $after_discount_stock_total;
    //     $history['final_stock_cost'] = $cost;
    //     $history['final_stock_qty'] = $history['current_move_stock_qty'];

    //     return $history;
    // }


    // ///
    // /**
    //  * @param $history
    //  *
    //  * @return mixed
    //  */
    // public function handlePurchaseExpensesHistroy($history)
    // {

    //     $expenses = $this->expenses()->where('invoice_id', $history['invoice_id'])->with('expense')->get();

    //     if (empty($expenses)) {
    //         return $history;
    //     }

    //     $expenses_data = [];
    //     foreach ($expenses as $expense) {

    //         $history['final_stock_total'] = $history['final_stock_total'] + $expense['amount']; // -
    //         $expense['expense_stock_total'] = $history['final_stock_total'];
    //         if ($history['final_stock_qty'] > 0) {
    //             $expense['expense_stock_cost'] = $history['final_stock_total'] / $history['final_stock_qty'];

    //         } else {
    //             $expense['expense_stock_cost'] = 0;
    //         }

    //         $expenses_data[] = $expense;
    //     }

    //     if ($history['final_stock_qty'] > 0) {
    //         $cost = $history['final_stock_total'] / $history['final_stock_qty'];
    //     } else {
    //         $cost = 0;
    //     }
    //     $history['final_stock_cost'] = $cost;
    //     $history['has_expenses'] = true;
    //     $history['expenses_data'] = $expenses_data;
    //     $history['total_cost'] = $cost * $history['qty'];
    //     return $history;
    // }

    // /**
    //  * @param $history
    //  * @param $cost
    //  * @param $stock_value
    //  * @param $stock_qty
    //  *
    //  * @return mixed
    //  */
    // public function handleReturnSaleHistory($history, $cost, $stock_value, $stock_qty)
    // {
    //     $history['current_move_stock_qty'] = $stock_qty + $history['qty'];
    //     $history['current_move_stock_total'] = $cost * $history['current_move_stock_qty'];
    //     $history['current_move_stock_cost'] = $cost;
    //     $history['total_cost'] = $cost * $history['qty'];
    //     $history['profits'] = $history['total'] - $history['total_cost'];
    //     if ($history['discount'] > 0) {
    //         $history = $this->handleReturnSaleDiscountHistory($history);

    //     }

    //     $history['profits'] = $history['profits'] * -1;

    //     $history['final_stock_cost'] = $history['current_move_stock_cost'];
    //     $history['final_stock_total'] = $history['final_stock_cost'] * $history['current_move_stock_qty'];
    //     $history['final_stock_qty'] = $history['current_move_stock_qty'];

    //     return $history;
    // }

    // /**
    //  * @param $history
    //  *
    //  * @return mixed
    //  */
    // public function handleReturnSaleDiscountHistory($history)
    // {

    //     $history['has_return_sale_discount'] = true;
    //     $history['discount_data'] = [
    //         'discount_profits' => $history['discount'],
    //         'discount_stock_total' => $history['current_move_stock_total'],
    //         'discount_stock_cost' => $history['current_move_stock_cost'],
    //     ];

    //     return $history;
    // }

    // /**
    //  * @param $history
    //  * @param $cost
    //  * @param $stock_value
    //  * @param $stock_qty
    //  *
    //  * @return mixed
    //  */
    // public function handleReturnPurchaseHistory($history, $cost, $stock_value, $stock_qty)
    // {

    //     $history['current_move_stock_qty'] = $stock_qty - $history['qty'];
    //     $history['current_move_stock_total'] = $stock_value - $history['total'];

    //     if ($history['current_move_stock_qty'] > 0) {
    //         $history['current_move_stock_cost'] = $history['current_move_stock_total'] / $history['current_move_stock_qty'];
    //     } else {
    //         $history['current_move_stock_cost'] = $cost;
    //     }

    //     $history['final_stock_total'] = $history['current_move_stock_total'];
    //     $history['final_stock_cost'] = $history['current_move_stock_cost'];
    //     $history['final_stock_qty'] = $history['current_move_stock_qty'];

    //     $history['total_cost'] = $cost * $history['qty'];

    //     if ($history['discount'] > 0) {
    //         $history = $this->handleReturnPurchaseDiscountHistory($history);
    //     }

    //     return $history;
    // }

    // /**
    //  * @param $history
    //  *
    //  * @return mixed
    //  */
    // public function handleReturnPurchaseDiscountHistory($history)
    // {

    //     $new_current_move_stock_total = $history['current_move_stock_total'] + $history['discount'];

    //     if ($history['current_move_stock_qty'] > 0) {
    //         $cost = $new_current_move_stock_total / $history['current_move_stock_qty'];
    //     } else {
    //         $cost = 0;
    //     }
    //     $history['has_return_purchase_discount'] = true;
    //     $history['discount_data'] = [
    //         'discount_stock_total' => $new_current_move_stock_total,
    //         'discount_stock_cost' => $cost,
    //     ];

    //     $history['final_stock_total'] = $new_current_move_stock_total;
    //     $history['final_stock_cost'] = $cost;
    //     $history['final_stock_qty'] = $history['current_move_stock_qty'];

    //     return $history;
    // }

    // /**
    //  * @param $history
    //  * @param $cost
    //  * @param $stock_value
    //  * @param $stock_qty
    //  *
    //  * @return mixed
    //  */
    // public function handleSaleHistory($history, $cost, $stock_value, $stock_qty)
    // {
    //     $history['current_move_stock_qty'] = $stock_qty - $history['qty'];
    //     $new_final_stock_total = $history['current_move_stock_qty'] * $cost;

    //     $history['current_move_stock_total'] = $new_final_stock_total;

    //     if ($history['current_move_stock_qty'] > 0) {
    //         $history['current_move_stock_cost'] = $history['current_move_stock_total'] / $history['current_move_stock_qty'];
    //     } else {
    //         $history['current_move_stock_cost'] = 0;

    //     }

    //     $history['total_cost'] = $cost * $history['qty'];
    //     $history['profits'] = $history['total'] - $history['total_cost'];

    //     $history['final_stock_total'] = $history['current_move_stock_total'];
    //     $history['final_stock_cost'] = $history['current_move_stock_cost'];
    //     $history['final_stock_qty'] = $history['current_move_stock_qty'];

    //     if ($history['discount'] > 0) {
    //         $history = $this->handleSaleDiscountHistory($history);
    //         $history['profits'] = $history['profits'] - $history['discount'];
    //     }

    //     return $history;

    // }

    // /**
    //  * @param $history
    //  *
    //  * @return mixed
    //  */
    // public function handleSaleDiscountHistory($history)
    // {

    //     $history['has_sale_discount'] = true;
    //     $history['discount_data'] = [
    //         'discount_stock_total' => $history['current_move_stock_total'],
    //         'discount_stock_cost' => $history['current_move_stock_cost'],
    //         'discount_profits' => $history['discount'] * -1,
    //     ];

    //     return $history;
    // }

    // public function handleAdjustStockHistory($history, $stock_value, $stock_qty)
    // {
    //     $result = $history;

    //     $result['current_move_stock_qty'] = $history['qty'];
    //     $result['current_move_stock_total'] = $stock_value + $history['total'];

    //     if ($result['current_move_stock_qty'] > 0) {
    //         $result['current_move_stock_cost'] = $result['current_move_stock_total'] / $result['current_move_stock_qty'];
    //     }

    //     if ($history['is_service']) {
    //         $result['current_move_stock_cost'] = 0;
    //     }

    //     $result['final_stock_total'] = $result['current_move_stock_total'];
    //     $result['final_stock_cost'] = $result['current_move_stock_cost'];
    //     $result['final_stock_qty'] = $result['current_move_stock_qty'];

    //     $result['description'] = 'جرد المخزون';
    //     return $result;
    // }

    // /**
    //  * @param $cost
    //  */
    // public function setCostAndAvailableQty($cost, $stock_qty)
    // {
    //     $this->update(
    //         [
    //             'cost' => $cost,
    //             'available_qty' => $stock_qty,
    //         ]
    //     );
    // }
}


