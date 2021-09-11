<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use Illuminate\Http\Response;

class DeliveryManController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return DeliveryMan::paginate(10);
    }
}
