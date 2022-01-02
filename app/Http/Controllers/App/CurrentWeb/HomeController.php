<?php

namespace App\Http\Controllers\App\CurrentWeb;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{

    public function index()
    {
        $workingYears = Auth::user()->organization->working_years;
        $activeYear = Auth::user()->active_year;
        return view('home.index', compact('workingYears', 'activeYear'));
    }

    public function changeSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'active_year' => ['nullable', 'integer', Rule::in(Auth::user()->organization->working_years)]
        ]);
        Auth::user()->update([
            'active_year' => $request->input('active_year')
        ]);
        return back()->with([]);
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return back();
    }
}

