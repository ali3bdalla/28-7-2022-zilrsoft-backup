<?php

namespace App;

use App\Attributes\KitAttributes;
use App\Core\CoreItem;
use App\DatabaseHelpers\FormattedQuery\ItemFormattedQuery;
use App\DatabaseHelpers\Invoice\ItemFreshHelper;
use App\DatabaseHelpers\ItemHelper;
use App\DatabaseHelpers\KitHelper;
use App\Processers\ItemProcesser;
use App\Relationships\ItemRelationships;
use App\Relationships\KitRelationships;
use App\Traits\OrmNumbersTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\DB;
use \Modules\Web\Models\WebItem;

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
 * @method static findOrFail($id)
 */
class Item extends BaseModel
{
    use ItemFreshHelper, KitAttributes, KitRelationships, ItemProcesser, ItemHelper, KitHelper;
    use CoreItem;

    // final traits
    use WebItem,OrmNumbersTrait,ItemRelationships,ItemFormattedQuery;




    use SoftDeletes;


    protected $appends = [
        'locale_name',
        'warranty_title'
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


    // new


    public function isQtyable()
    {
        return $this->isCostableItem();
    }


    public function isExpense()
    {
        return $this->is_expense;
    }


    public function getSalePrice($userPrice = 0)
    {
        return $this->is_fixed_price ? $this->price : $userPrice;
    }


    public function getSaleTax($subtotal = 0)
    {
        return (float) $subtotal * $this->vts / 100;
    }

    public function getPurchaseTaxAsFloatValue()
    {
        return (1 + $this->vtp) / 100;
    }
    public function getSaleTaxAsFloatValue()
    {
        return (1 + $this->vts / 100);
    }
    public function availableQty()
    {
        return (int)$this->available_qty;
    }

    public function isCostableItem()
    {
        return !$this->is_kit && !$this->is_service;
    }

    public function isNeedSerial()
    {
        return $this->is_need_serial;
    }

    public function isKit()
    {
        return $this->is_kit;
    }

    public function isService()
    {
        return $this->is_service;
    }
    // old


    public function getFormattedTitleAttribute()
    {
        return $this->name;
    }

    public function getFormattedPriceAttribute()
    {
        $price = $this->price_with_tax;
        if ($this->is_kit)
            $price = $this->data->net;

        return money_format("%i SAR", $price);
    }


    public function getWarrantyTitleAttribute()
    {
        if (!empty($this->warranty))
            return $this->warranty->locale_name;

        return "";
    }

    public function getSalesCountAttribute()
    {

        if ($this->statistics)
            return $this->statistics->sales_count;

        return 0;
    }

    public function getLocaleWarranyAttribute()
    {
        if ($this->warranty) {
            if (app()->isLocate('ar'))
                return $this->warranty->ar_name;
            else
                return $this->warranty->name;
        }

        return null;
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->toDateString();
    }

    public function getDataForReturn($qty, $fresh_invoice_item)
    {

        $data['total'] = $fresh_invoice_item->price * $qty;
        $data['discount'] = $fresh_invoice_item->discount / $fresh_invoice_item->qty * $qty;
        $data['tax'] = $fresh_invoice_item->tax / $fresh_invoice_item->qty * $qty;

        $data['subtotal'] = $data['total'] - $data['discount'];
        $data['net'] = $data['subtotal'] + $data['tax'];
        $data['organization_id'] = $fresh_invoice_item->organization_id;
        $data['item_id'] = $this->id;
        $data['user_id'] = $fresh_invoice_item->user_id;
        $data['price'] = $fresh_invoice_item->price;
        $data['creator_id'] = auth()->user()->id;
        return $data;
    }

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
        $pserials = ItemSerials::where('serial', $search)->whereIn('current_status', ['available', 'r_sale'])->get();
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

    public function updateItemAvailableQty($option, $qty)
    {
        if ($option == 'add') {
            $this->update([
                'available_qty' => DB::raw("available_qty + $qty ")
            ]);
            return true;
        } else {

            $this->update([
                'available_qty' => DB::raw("available_qty - $qty ")
            ]);
            return true;
        }
    }

    public function updateSalesPrice($price_with_tax)
    {
        if (is_numeric($price_with_tax)) {
            $price_without_tax = $price_with_tax / $this->getItemVatSaleAsValue();
            $this->update([
                'price' => $price_without_tax,
                'price_with_tax' => $price_with_tax
            ]);
        }
    }

    public function getItemVatSaleAsValue()
    {
        return 1 + $this->vts / 100;
    }

    public function updateLastPurchasePrice($last_price)
    {
        $this->update([
            'last_p_price' => $last_price
        ]);
    }

    public function setSerialAs($status, $serials = [], $invoice = null)
    {
        if ($status == 'r_sale') {
            $data = [
                'current_status' => $status,
                'r_sale_invoice_id' => $invoice->id,
            ];
            $user_id = $invoice->sale->client_id;
        } else {
            $data = [
                'current_status' => $status,
                'r_purchase_invoice_id' => $invoice->id,
            ];
            $user_id = $invoice->purchase->vendor_id;
        }
        $this->serials()->whereIn('id', collect($serials)->pluck('id')->toArray())->update($data);
//
//        print_r($invoice);
//        exit();

        foreach ($serials as $serial) {
            $invoice->serial_history()->create([
                'event' => $status,
                'organization_id' => auth()->user()->organization_id,
                'creator_id' => auth()->user()->id,
                'serial_id' => $serial['id'],
                'user_id' => $user_id
            ]);
        }


    }

    public function validatePurchaseData($data)
    {

        return true;

    }

    public function checkIfItHasEnoughQtyForReturn($qty, $fresh_invoice_item, $sub_invoice)
    {
        $total_returned = $qty + $fresh_invoice_item['r_qty'];
        if ($sub_invoice instanceof PurchaseInvoice) {
            if ($qty > $this->available_qty)
                return false;
        }

        if ($total_returned > $fresh_invoice_item['qty'])
            return false;


        return true;
    }

    public function getListOfReturnedSerial($serials, $return_invoice)
    {

        $result = [];

        foreach ($serials as $serial) {
            if ($serial['current_status'] == $return_invoice) {
                $fresh_serial = $this->serials()->where('id', $serial['id'])->first();
                if ($fresh_serial['current_status'] != $return_invoice) {
                    $result[] = $serial;
                }
            }
        }


        return $result;
    }

    public function getLocaleNameAttribute()
    {
        if (app()->isLocale('ar')) {
            return $this->ar_name;
        }


        return $this->name;
    }

    public function getPriceAttribute($value)
    {

//        return 0;
        return  $this->moneyFormatter($value);
    }

    public function getTotalAttribute($value)
    {


        return  $this->moneyFormatter($value);
    }

    public function getSubtotalAttribute($value)
    {


        return  $this->moneyFormatter($value);
    }

    public function getPriceWithTaxAttribute($value)
    {


        return  $this->moneyFormatter($value);
    }

    public function getLastPriceAttribute($value)
    {


        return  $this->moneyFormatter($value);
    }



}
