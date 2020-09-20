<?php

namespace App\Attributes;

trait InvoiceAttributes
{

  


    
    // public function isPurchase()
    // {
    //     return in_array($this->invoice_type, ['purchase', 'beginning_inventory']);
    // }

    // public function isReturnPurchase()
    // {
    //     return in_array($this->invoice_type, ['return_purchase']);
    // }

    // public function isSale()
    // {
    //     return in_array($this->invoice_type, ['sale']);
    // }

    // public function isReturnSale()
    // {
    //     return in_array($this->invoice_type, ['return_sale']);
    // }

    // public function isPending()
    // {
    //     return in_array($this->invoice_type, ['pending_purchase', 'quotation']);
    // }

    // public function getPrintableTaxAndNetAttribute()
    // {
    //     $result['tax'] = 0;
    //     $result['net'] = 0;
    //     $expenses = $this->expenses()->sum('amount');
    //     $items = $this->items()
    //         ->where([['belong_to_kit', false]])
    //         ->get();
    //     foreach ($items as $item) {
    //         $result['tax'] = $result['tax'] + $item->printable_tax;
    //         $result['net'] = $result['net'] + $item->printable_net;
    //     }
    //     $netAmount = $result['net'] - $expenses;

    //     $result['net'] = $this->roundOnLessThan1Cent($netAmount);
    //     return $result;
    // }

    // public function getUserIdAttribute()
    // {
    //     if (in_array($this->invoice_type, ['sale', 'return_sale', 'quotation'])) {
    //         return !empty($this->sale) ? $this->sale->client_id : 0;
    //     }

    //     return !empty($this->purchase) ? $this->purchase->vendor_id : 0;
    // }

    // public function getNameAttribute()
    // {
    //     return $this->purchase()->prefix;
    // }

    // public function setCreationStatusAs($status)
    // {
    //     if (in_array($status, ['paid', 'credit']))
    //         $this->update(['current_status' => $status, 'issued_status' => $status]);

    //     return $this;
    // }

    // public function setTypeAs($type)
    // {
    //     $this->update(['invoice_type' => $type]);
    // }

    // public function getPdfInvoicePath()
    // {

    // }

    // public function setPdfInvoicePath($path)
    // {
    //     $this->update([
    //         'pdf_invoice' => $path
    //     ]);
    // }

    // public function setAsReturnedInvoice()
    // {
    //     return $this->update([
    //         'is_updated' => true
    //     ]);
    // }

    // public function getTotalTaxAttribute()
    // {
    //     return $this->tax + $this->expenses()->sum('tax');
    // }

    // public function makeInvoiceUpdatedOrDeleted()
    // {
    //     $data['is_updated'] = true;
    //     $data['is_deleted'] = true;

    //     foreach ($this->items as $item) {
    //         if ($item['qty'] != $item['r_qty']) {
    //             $data['is_deleted'] = false;
    //         }
    //     }
    //     $this->update($data);
    // }

    // public function getVat($type)
    // {
    //     if ($type == 'purchase') {
    //         return 5;
    //     }
    // }

    // public function getDescriptionAttribute()
    // {
    //     $description = '';
    //     if (app()->isLocale('ar')) {
    //         if ($this->invoice_type == 'purchase')
    //             $description = ' مشتريات';

    //         if ($this->invoice_type == 'quotation')
    //             $description = ' عرض سعر';

    //         if ($this->invoice_type == 'sale')
    //             $description = 'مبيعات';

    //         if ($this->invoice_type == 'beginning_inventory')
    //             $description = 'اول مدة';

    //         if ($this->invoice_type == 'return_sale')
    //             $description = 'مرتجع مبيعات';

    //         if ($this->invoice_type == 'return_purchase')
    //             $description = 'مرتجع مشتريات';

    //         if ($this->invoice_type == 'stock_adjust')
    //             $description = 'جرد المخزون';

    //     } else {
    //         if ($this->invoice_type == 'sale')
    //             $description = 'sale';

    //         if ($this->invoice_type == 'quotation')
    //             $description = 'quotation';

    //         if ($this->invoice_type == 'stock_adjust')
    //             $description = 'stock adjustment ';

    //         if ($this->invoice_type == 'purchase')
    //             $description = 'purchase';

    //         if ($this->invoice_type == 'beginning_inventory')
    //             $description = 'beginning inventory';

    //         if ($this->invoice_type == 'return_sale')
    //             $description = 'return sale';

    //         if ($this->invoice_type == 'return_purchase')
    //             $description = 'return purchase';

    //     }

    //     return $description;
    // }

    // public function getTitleAttribute()
    // {

    //     if (in_array($this->invoice_type, ['sale', 'return_sale', 'quotation']))
    //         return !empty($this->sale) ? $this->sale->prefix . $this->id : "";
    //     elseif (!empty($this->purchase))
    //         return $this->purchase->prefix . $this->id;
    //     elseif ($this->invoice_type == 'stock_adjust' && $this->is_deleted == true)
    //         return "INC-" . $this->id;
    //     elseif ($this->invoice_type == 'stock_adjust' && $this->is_deleted == false)
    //         return "STR-" . $this->id;

    // }

    // public function getCreatedDateAttribute()
    // {
    //     return $this->created_at->diffForHumans();
    // }

    // public function getSteakholderNameAttribute()
    // {
    //     if (in_array($this->invoice_type, ['sale', 'return_sale', 'quotation'])) {
    //         return $this->sale->alice_name == null ? $this->sale->client->locale_name : $this->sale->alice_name;
    //     }

    //     return $this->purchase->vendor->locale_name;
    // }

    // public function getSteakholderTypeAttribute()
    // {
    //     if (in_array($this->invoice_type, ['sale', 'return_sale', 'quotation'])) {
    //         return __('pages/invoice.client');
    //     }

    //     return __('pages/invoice.vendor');
    // }

    // //served_title
    // public function getServedTitleAttribute()
    // {
    //     if (in_array($this->invoice_type, ['sale', 'return_sale', 'quotation'])) {
    //         return __('pages/invoice.salesman');
    //     }

    //     return __('pages/invoice.receiver');
    // }

    // public function getSteakholderPhoneNumberAttribute()
    // {
    //     if (in_array($this->invoice_type, ['sale', 'return_sale', 'quotation'])) {
    //         return $this->sale->client->phone_number;
    //     }

    //     return $this->purchase->vendor->phone_number;
    // }

    // public function getServedByAttribute()
    // {
    //     if (in_array($this->invoice_type, ['sale', 'return_sale', 'quotation'])) {
    //         return $this->sale->salesman->locale_name;
    //     }

    //     return $this->purchase->receiver->locale_name;
    // }

    public function getBackgroundAssetAttribute()
    {

        if ($this->invoice_type == 'quotation') {
            if (app()->isLocale('ar'))
                return asset('template/images/quotation-ar.png');
            else
                return asset('template/images/quotation.png');

        }

        if ($this->current_status == 'paid') {
            if (app()->isLocale('ar'))
                return asset('template/images/paid-ar.png');
            else
                return asset('template/images/paid.png');

        } else {

            return ":";

        }

    }

    // public function getPriceAttribute($value)
    // {

    //     return $this->moneyFormatter($value);
    // }

    // public function getTotalAttribute($value)
    // {
    //     return $this->moneyFormatter($value);
    // }

    // public function getTaxAttribute($value)
    // {
    //     return $this->moneyFormatter($value);
    // }

    // public function getDiscountValueAttribute($value)
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

}
