<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\Filter\ApiGetFiltersRequest;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function apiGetFilters(ApiGetFiltersRequest $request)
    {
        return $request->getData();
    }
}
