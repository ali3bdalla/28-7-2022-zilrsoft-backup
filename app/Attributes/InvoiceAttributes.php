<?php

namespace App\Attributes;

trait InvoiceAttributes
{

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

    public function getBackgroundAssetAttribute()
    {

        if ($this->invoice_type == 'quotation') {
            if (app()->isLocale('ar')) {
                return asset('template/images/quotation-ar.png');
            } else {
                return asset('template/images/quotation.png');
            }

        }

        if (app()->isLocale('ar')) {
            return asset('template/images/paid-ar.png');
        } else {
            return asset('template/images/paid.png');
        }

    }

    public function getFinalUserNameAttribute()
    {
        if (in_array($this->invoice_type, ['sale', 'return_sale']) && $this->sale->alice_name != null) {
            return $this->sale->alice_name;
        }

        return $this->user->name;
    }

}
