<?php

namespace App\Http\Resources\Accountin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TrialBalanceCollection extends ResourceCollection
{
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