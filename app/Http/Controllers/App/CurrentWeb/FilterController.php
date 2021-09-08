<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use App\Models\Filter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\View\View;

class FilterController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {

        return view('accounting.filters.index');

    }

    public function create()
    {
        return view('accounting.filters.create');
    }


    /**
     * @param Filter $filter
     *
     * @return Factory|View
     */
    public function show(Filter $filter)
    {

        return view('accounting.filters.show');
        //
    }

    /**
     * @param Filter $filter
     *
     * @return Factory|View
     */
    public function edit(Filter $filter)
    {
        return view('accounting.filters.edit', compact('filter'));
        //
    }


}
