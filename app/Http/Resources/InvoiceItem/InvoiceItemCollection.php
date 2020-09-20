<?php

namespace App\Http\Resources\InvoiceItem;

use Illuminate\Http\Resources\Json\ResourceCollection;

class InvoiceItemCollection extends ResourceCollection
{

    public $collects = InvoiceItemResource::class;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
