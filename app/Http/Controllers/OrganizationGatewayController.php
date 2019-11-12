<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentMethodForm;
use App\Gateway;
class OrganizationGatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	
	    $payments_methods = auth()->user()->organization->gateways;
//        return $payments_methods;
        return view('settings.ways.index',compact('payments_methods'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('settings.ways.create');
        //
    }






    public  function  fetch(){
        return auth()->user()->organization->gateways;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePaymentMethodForm $request)
    {
        return  $request->save();
        //
    }

	public function destroy(Gateway $payWay)
    {
    	     auth()->user()->organization->gateways()->detach($payWay->id);
          return  back();
        //
    }



}
