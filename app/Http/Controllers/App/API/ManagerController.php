<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index(Request $request)
    {
        $mainQuery = Manager::query();
        return $mainQuery->get();
    }
}
