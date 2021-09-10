<?php

namespace App\Http\Resources\Entity;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    protected $preserveAllQueryParameters = true;

    /**
     * Transform the resource into a JSON array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {

        return $this->collection->map(function ($item, $key) {
            return new TransactionResource($item, $key);
        })->toArray();
    }
}
