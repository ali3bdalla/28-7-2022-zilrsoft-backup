<?php

namespace App\Http\Controllers\App\Web;

use App\Http\Controllers\Controller;
use App\Models\WarrantySubscription;
use Illuminate\Http\Response;

class WarrantySubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return WarrantySubscription::all();
    }

}
