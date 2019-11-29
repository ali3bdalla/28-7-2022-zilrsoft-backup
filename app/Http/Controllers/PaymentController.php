<?php
	
	namespace App\Http\Controllers;
	
	use App\CountryBank;
	use App\Http\Requests\CreatePaymentRequest;
	use App\Http\Requests\CreateReceiptRequest;
	use App\Payment;
	use App\User;
	
	class PaymentController extends Controller
	{
		
		public function index()
		{
			$type = 'all';
			$payments = Payment::orderBy('id','desc')->paginate(15);
			return view('payments.index',compact('payments','type'));
		}
		
		public function show(Payment $payment)
		{
			
//			return $payment;
			return view('payments.show',compact('payment'));
		}
		
		public function payments()
		{
			$type = 'payment';
			$payments = Payment::where('payment_type','payment')->paginate(15);
			return view('payments.index',compact('payments','type'));
		}
		
		public function receipts()
		{
			$type = 'receipt';
			$payments = Payment::where('payment_type','receipt')->paginate(15);
			return view('payments.index',compact('payments','type'));
		}
		
		public function create_receipt()
		{
			$organization_accounts = [];
			$organization_gateways = auth()->user()->organization->gateways()->where('gateways.id','!=',7)->with('fields')->get();
			$source_organization_accounts = auth()->user()->organization->accounts()->where('gateway_id',2)->get()->groupBy('bank_id');
			if (!empty($source_organization_accounts)){
				foreach ($source_organization_accounts as $index => $accounts){
					$bank = CountryBank::find($index);
					$bank->organization_accounts = $accounts;
					$organization_accounts[] = $bank;
					
				}
				
			}
			$all_banks = CountryBank::all();
//			return $banks;
			$clients = [];
			$vendors = [];
			$source_client = User::clients()->get();//clients()->
			if (!empty($source_client)){
				foreach ($source_client as $client){
					$banks = [];
					foreach ($client->accounts()->where('gateway_id',2)->get()->groupBy('bank_id') as $bank_id =>
					         $accounts){
						$bank = CountryBank::find($bank_id);
						$bank->user_accounts = $accounts;
						$banks[] = $bank;
					}
					$client['banks'] = $banks;
					$clients[] = $client;
				}
			}
			
			return view('payments.create_receipt',compact('organization_gateways','all_banks','vendors','clients','organization_accounts'));
			//
		}
		
		public function create_payment()
		{
			
			
			$organization_accounts = [];
			$organization_gateways = auth()->user()->organization->gateways()->where('gateways.id','!=',7)->with('fields')->get();
			$source_organization_accounts = auth()->user()->organization->accounts()->where('gateway_id',2)->get()->groupBy('bank_id');
			if (!empty($source_organization_accounts)){
				foreach ($source_organization_accounts as $index => $accounts){
					$bank = CountryBank::find($index);
					$bank->organization_accounts = $accounts;
					$organization_accounts[] = $bank;
					
				}
				
			}
			$all_banks = CountryBank::all();
			$clients = [];
			$vendors = [];
			$source_vendors = User::vendors()->get();//clients()->
			if (!empty($source_vendors)){
				foreach ($source_vendors as $vendor){
					$banks = [];
					foreach ($vendor->accounts()->where('gateway_id',2)->get()->groupBy('bank_id') as $bank_id =>
					         $accounts){
						$bank = CountryBank::find($bank_id);
						$bank->user_accounts = $accounts;
						$banks[] = $bank;
					}
					$vendor['banks'] = $banks;
					$vendors[] = $vendor;
				}
			}
			
			
			return view('payments.create_payment',compact('organization_gateways','all_banks','vendors','clients','organization_accounts'));
			//
		}
		
		public function store_receipt(CreateReceiptRequest $request)
		{
			return $request->save();
		}
		
		public function store_payment(CreatePaymentRequest $request)
		{
			return $request->save();
		}
		
	}
