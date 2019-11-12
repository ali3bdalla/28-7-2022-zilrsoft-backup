<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Gateway;
use App\Http\Requests\CreatePurchaseInvoice;
use App\Http\Requests\CreateReturnPurchaseRequest;
use App\PurchaseInvoice;
use App\User;
use function foo\func;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $purchases =  PurchaseInvoice::whereIn('invoice_type',['purchase','r_purchase'])->with('invoice')
	        ->orderBy('id','desc')->paginate(20);

        return view('purchases.index',compact('purchases'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	
	    $receivers = User::where('is_manager',true)->get()->toArray();
	    $vendors = User::where([['is_vendor',true],['is_system_user',false]])->get()->toArray();
	
	    $expenses = Expense::where('appear_in_purchase',true)->get();
	    // return ;
	    $gateways = Gateway::whereIn('id',[1,3,4])->whereIn('id',auth()->user()->organization->gateways->pluck
	    ('pivot.gateway_id'))->get();
	    
	    
//        $vendors = User::vendors()->get();
//        $receivers = User::managers()->get();
	
//	    $gateways = Gateway::find([1,2,3]);
//        return  $receivers;
        return  view('purchases.create',compact('vendors','receivers','gateways','expenses'));
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePurchaseInvoice $request)
    {


//        return  $request->all();
        return  $request->save();
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PurchaseInvoice  $purchaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseInvoice $purchase)
    {

        return  view('purchases.show',compact('purchase'));
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PurchaseInvoice  $purchaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseInvoice $purchase)
    {

        $invoice = $purchase->invoice;
        // items
        $items = [];
        foreach ($purchase->invoice->items()->with('item')->get() as $item) {
            if($item->item->is_need_serial){
                $item['serials'] = $item->item->serials()->purchase($invoice->id)->get();
            }
            $items [] = $item;
        }
	
	
        
//        return $items;


        return  view('purchases.edit',compact('purchase','invoice','items'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PurchaseInvoice  $purchaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function update(CreateReturnPurchaseRequest $request, PurchaseInvoice $purchase)
    {
        return $request->save($purchase);
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PurchaseInvoice  $purchaseInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseInvoice $purchaseInvoice)
    {
        //
    }

    public function unpaid(User $user)
    {
        return PurchaseInvoice::where([
            ['vendor_id',$user->id]
        ])->with('invoice.creator','vendor')->whereHas('invoice',function ($query)
        {
            return $query->where('current_status','credit');
        })->get();
    }
	
	
	public function unpaid_all(User $user)
	{
		return PurchaseInvoice::with('invoice.creator','vendor')->whereHas('invoice',function ($query)
		{
			return $query->whereIn('current_status',['credit']);
		})->get();
	}
	
	

}


