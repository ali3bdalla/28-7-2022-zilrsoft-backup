<?php

namespace App\Http\Resources\Entity;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    protected $preserveAllQueryParameters = true;
//    public $collects = TransactionResource::class;

    /**
     * Transform the resource into a JSON array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->map(function($item,$key) {
            return new TransactionResource($item,$key);
        })->toArray($request);
//        return $this->collection->map->toArray($request)->all();
    }
}
