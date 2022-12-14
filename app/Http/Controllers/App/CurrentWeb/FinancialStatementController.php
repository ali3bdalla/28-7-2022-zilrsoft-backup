<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinancialStatementController extends Controller
{

    public function index()
    {
        return view('accounting_module.financial_statements.index');
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function trailBalance(Request $request)
    {


        return view('accounting_module.financial_statements.trial_balance');
    }

}
