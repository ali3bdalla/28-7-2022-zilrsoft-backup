<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\FilterUsersRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index(FilterUsersRequest $request)
    {
        $userQuery = User::query();
        $userQuery = $userQuery->when($request->customerOnly(), function ($query) {
            return $query->whereIsClient(true);
        });
        $userQuery = $userQuery->when($request->vendorOnly(), function ($query) {
            return $query->whereIsVendor(true);
        });
        return $userQuery->get();
    }
}
