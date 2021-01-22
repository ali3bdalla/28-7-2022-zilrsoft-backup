<?php

namespace App\Http\Controllers\BackEnd\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Store\Shipping\StoreShippingMethodDeliveryManRequest;
use App\Http\Requests\Backend\Store\Shipping\UpdateShippingMethodRequest;
use App\Jobs\External\Smsa\DownloadShippmentPdfJob;
use App\Jobs\External\Smsa\GetShippmentStatusJob;
use App\Jobs\External\Smsa\SmsaCreateShippmentJob;
use App\Jobs\Order\Shipping\HandleOrderShippingJob;
use App\Models\City;
use App\Models\DeliveryMan;
use App\Models\Item;
use App\Models\Order;
use App\Models\ShippingMethod;
use App\Models\ShippingTransaction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
        $deliveryMen = $shipping->deliveryMen()->get();

        return view('backend.store.shipping.transactions',compact('shipping','deliveryMen'));
    }


    public function fetchTransactions(ShippingMethod $shipping)
    {
        return $shipping->transactions()->with('city','creator','order',"deliveryMan")->orderBy('id','desc')->paginate(30);
    }
    public function createTransaction(ShippingMethod $shipping)
    {
        $citites = City::orderBy('name')->get();

        return view('backend.store.shipping.create-transaction',compact('shipping','citites'));
    }


    public function downloadTransaction(ShippingMethod $shipping,ShippingTransaction $transaction)
    {
        // return GetShippmentStatusJob::dispatchNow($transaction);
        return DownloadShippmentPdfJob::dispatchNow($transaction);
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
        $data['shipping_method_id'] = $shipping->id;
        $data['creator_id'] =  auth()->user()->id;
        $data['organization_id'] = auth()->user()->organization_id;
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
        

        
        return redirect(route('store.shipping.view_transactions',$shipping->id));
    }


    public function signTransactionsToDeliveryMan( Request $request)
    {
        $request->validate([
			"delivery_man_id" => "required|integer|exists:delivery_men,id",
			"transactions" => "required|array",
			"transactions.*" => "required|integer|exists:shipping_transactions,id",
		]);
		$deliveryMan = DeliveryMan::findOrFail($request->input('delivery_man_id'));

		$phoneNumber = '+966556045415';//$deliveryMan->phone_number
		$otp = generateOtp();
		sendOtp($phoneNumber, $otp);

		// Whatsapp::sendMessage(
		// 	"verification code for accepting order {$order->id} is: {$otp }", [$phoneNumber]
		// );
		$deliveryMan->verfications()->create([
			'slug' => 'transactions_' . implode('-',$request->input('transactions')),
			'verfication_code' => $otp
		]);
    }


	public function activateSignTransactionsToDeliveryMan(Request $request)
	{
		$request->validate([
            "delivery_man_id" => "required|integer|exists:delivery_men,id",
			"transactions" => "required|array",
			"transactions.*" => "required|integer|exists:shipping_transactions,id",
			"verification_code" => "required|integer",
		]);

		$deliveryMan = DeliveryMan::findOrFail($request->input('delivery_man_id'));
		$verification = $deliveryMan->verfications()->where([
			['slug', 'transactions_' . implode('-',$request->input('transactions')),]
		])->orderBy('id','desc')->first();

		if($verification && $verification->verfication_code == $request->input('verification_code')) {
            $transactions = ShippingTransaction::find($request->input('transactions'));
            foreach ($transactions as $key => $transaction) {
                if($transaction->status === 'issued')
                {
                    $transaction->update([
                        'status' => 'shipped',
                        'delivery_man_id' => $request->input('delivery_man_id')
                    ]);
                    if($transaction->order && $transaction->order->status == 'ready_for_shipping') {
                        HandleOrderShippingJob::dispatchNow($transaction->order,$deliveryMan);
                    }
                }
            }
		}else{

			throw  ValidationException::withMessages([]);
		}

	}
}