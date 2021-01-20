<?php

namespace App\Http\Controllers\BackEnd\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Store\Shipping\StoreShippingMethodDeliveryManRequest;
use App\Http\Requests\Backend\Store\Shipping\UpdateShippingMethodRequest;
use App\Jobs\External\Smsa\SmsaCreateShippmentJob;
use App\Models\City;
use App\Models\Item;
use App\Models\Order;
use App\Models\ShippingMethod;
use App\Models\ShippingTransaction;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        return view('backend.store.shipping.index', [
            'shippingMethods' => ShippingMethod::paginate(20)
        ]);
    }


    public function edit(ShippingMethod $shipping)
    {
        $expensesItems = Item::where('is_expense', true)->get();


        return view('backend.store.shipping.edit', [
            'deliveryMen' => $shipping->deliveryMen()->get(),
            'shipping' => $shipping,
            'expenses' => $expensesItems,
            'citiesList' => City::all(),
            'shippingCities' => $shipping->cities()->pluck('id')->toArray()
        ]);
    }


    public function update(ShippingMethod $shipping, UpdateShippingMethodRequest $request)
    {
        return $request->update($shipping);
    }


    public function storeDeliveryMan(ShippingMethod $shipping, StoreShippingMethodDeliveryManRequest $request)
    {
        return $request->store($shipping);
    }


    public function viewTransactions(ShippingMethod $shipping)
    {

        return view('backend.store.shipping.transactions',compact('shipping'));
    }

    public function createTransaction(ShippingMethod $shipping)
    {
        $citites = City::orderBy('name')->get();

        return view('backend.store.shipping.create-transaction',compact('shipping','citites'));
    }

    public function createOrderTransaction(ShippingMethod $shipping,Order $order)
    {
        $citites = City::orderBy('name')->get();

        return view('backend.store.shipping.create-order-transaction',compact('shipping','citites','order'));
    }


    public function storeTransaction(ShippingMethod $shipping,Request $request)
    {
        $request->validate([
            'phone_number' => "required|string",
            'first_name' => "required|string",
            'last_name' => "required|string",
            'city_id' => "required|integer|exists:cities,id",
            'order_id' => "nullable|integer|exists:orders,id",
            'address' => "required|string",
            'reference' => "required|string",
            'description' => "required|string",
            'cod' => "required|integer",
            'boxes' => "required|integer",
            'weight' => "required|numeric"
        ]);


        $data = $request->only(
            "first_name",
            "last_name",
            "city_id",
            "phone_number",
            "order_id",
            "address",
            "reference",
            "description",
            "cod",
            "boxes",
            "weight",
        );

        

        $created = false;
        if($request->filled('order_id'))
        {
            $orderTransaction = ShippingTransaction::where('order_id',$request->input('order_id'))->first();

            if($orderTransaction)
            {
                $created  = true;
            }
        }



        if(!$created)
        {
           
            $refernence = ShippingTransaction::where('reference',$request->input('reference'))->first();
            if( $refernence)
            {
                $data['reference'] = uniqid();
            }

            $data['tracking_number'] = SmsaCreateShippmentJob::dispatchNow($data);
            ShippingTransaction::create($data);
        }
        

        
        return redirect(route('store.shipping.transactions',$shipping->id));
    }
}