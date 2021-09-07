<?php

namespace App\Http\Controllers\Store\API;

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
