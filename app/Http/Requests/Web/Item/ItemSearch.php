<?php

namespace App\Http\Requests\Web\Item;

use App\Jobs\Utility\Str\ReplaceArabicSensitiveCharJob;
use Illuminate\Database\Eloquent\Builder;

trait ItemSearch
{
    public function apply(Builder $query): Builder
    {
        if ($this->has('name') && $this->filled('name')) {
            $searchKeywords = ReplaceArabicSensitiveCharJob::dispatchSync($this->input('name'));
            $searchArray = explode(' ', $this->input('name'));

            if ($this->has('search_via') && $this->filled('search_via') && $this->input('search_via') == 'tag') {
                $query->whereHas('tags', function ($qqq) {
                    $qqq->where('tag', $this->input('name'));
                });
            } else {
                $query->whereHas('tags', function ($qqq) use ($searchKeywords) {
                    $qqq->where('tag', $searchKeywords);
                })->orWhere(function ($subQueryLevel) use ($searchArray) {
                    foreach ($searchArray as $searchKey) {
                        $searchKey = ReplaceArabicSensitiveCharJob::dispatchSync($searchKey);
                        $subQueryLevel->orWhere('ar_name', 'iLIKE', '%' . $searchKey . '%')->orWhere('name', 'iLIKE', '%' . $searchKey . '%');
                    }
                });
            }


        }

        return $query;
    }


}
