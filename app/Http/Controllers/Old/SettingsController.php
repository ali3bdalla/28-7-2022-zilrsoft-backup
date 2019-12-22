<?php

namespace App\Http\Controllers;

use App\CountryBank;
use App\Http\Requests\CreateMethodsAccountRequest;
use App\Gateway;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
	    return view('settings.index');
    }

    public function payment_accounts()
    {
        $accounts =  auth()->user()->organization->accounts()->with('gateway','bank')->get()->groupBy('gateway_id');

        
        return view('settings.payment_accounts',compact('accounts'));
    }


    public function payments_account_create()
    {
	    $organization_gateways = auth()->user()->organization->gateways()->where('gateways.is_has_fields',true)->with('fields')->get();
	    $banks = CountryBank::all();
	     return view('settings.payment_account_create',compact('organization_gateways','banks'));
    }


    public function payments_account_store(CreateMethodsAccountRequest $request)
    {
	    $data = $request->except('user_id');
        $data['organization_id'] = auth()->user()->organization_id;
        auth()->user()->organization->accounts()->create($data);
        return route('management.settings.payment_accounts');
    }
    //
	
	
	public function local_printers()
	{
		return view('settings.general.printers');
	}
}
