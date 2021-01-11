<?php

namespace App\Http\Requests\Web\Item;

use Illuminate\Database\Eloquent\Builder;

trait ItemSearch
{
    public function apply(Builder $query)
    {
        if ($this->has('name') && $this->filled('name')) {
            $searchArray = explode(' ', $this->input('name'));

            $query->where(function ($subQueryLevel) use ($searchArray) {
                foreach ($searchArray as $searchKey) {
                    $subQueryLevel->orWhere('ar_name', 'iLIKE', '%' . $searchKey . '%')->orWhere('name', 'iLIKE', '%' . $searchKey . '%');
                }
            });
        }

        return $query;
    }
}
