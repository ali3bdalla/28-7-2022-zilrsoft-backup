<?php

namespace App\Http\Resources\InvoiceItem;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {

        // return [
        //     'id' => $this->id,
        //     'invoice_type' => $this->invoice_type,
        //     'tax' => $this->tax,

        // ];
        return parent::toArray($request);
    }
}
