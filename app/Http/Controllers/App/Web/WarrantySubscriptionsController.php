<?php

namespace App\Http\Controllers\App\Web;

use App\Http\Controllers\Controller;
use App\Models\WarrantySubscription;
use Illuminate\Database\Eloquent\Collection;

class WarrantySubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection
     */
    public function index(): Collection
    {
        return WarrantySubscription::all();
    }

}
