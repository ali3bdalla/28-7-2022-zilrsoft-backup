<?php


namespace Modules\Web\Models;


trait WebItem
{
    public function getWebMasterImageUrlAttribute()
    {
        $attachments = $this->attachments()->where('type','image');
        return $attachments->count() > 0 ? $attachments->first()->url : '/Web/template/img/products/man-3.jpg';
    }

    public function getWebOnlinePriceAttribute()
    {
        return $this->price_with_tax;
    }

    public function getWebOldOnlinePriceAttribute()
    {
        return $this->price_with_tax + 10 ;
    }


    public function getWebOnlineDiscountAttribute()
    {
        return 10;
    }

    public function getWebNameAttribute()
    {
        return $this->locale_name;
    }


}