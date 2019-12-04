<?php
	
	namespace App\Http\Controllers;
	
	use App\Account;
	use App\Http\Requests\CreateReceiptRequest;
	use App\Http\Requests\CreateVoucherRequest;
	use App\Payment;
	use App\User;
	use Illuminate\Http\Request;
	
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
			$current_assets_account = auth()->user()->get_active_manager_account_for('current_assets');
			
			
			$accounts = Account::where(
				[
					['slug','gateway'],
					['parent_id',$current_assets_account->id]
				]
			)
				->with(
					'children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children'
				)->get();
			
			$users = User::where('is_client',true)->with('gateways.bank')->get();
			
			
			$voucher_types = config('global.voucher_types');
//
//			return $users
			return view('payments.create_receipt',compact('accounts','users','voucher_types'));
			//
			
		}
		
		public function create_payment()
		{
			
			$current_assets_account = auth()->user()->get_active_manager_account_for('current_assets');
			
			
			$accounts = Account::where(
				[
					['slug','gateway'],
					['parent_id',$current_assets_account->id]
				]
			)
				->with(
					'children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children.children'
				)->get();
			
			$users = User::where('is_vendor',true)->with('gateways.bank')->get();
			
			
			$voucher_types = config('global.voucher_types');
//
//			return $users
			return view('payments.create_payment',compact('accounts','users','voucher_types'));
			//
		}
		
		public function store(CreateVoucherRequest $request)
		{
			
			return $request->save();
		}
		
		public function store_receipt(CreateReceiptRequest $request)
		{
			return $request->save();
		}
		
//		public function store_payment(CreatePaymentRequest $request)
//		{
//			return $request->save();
//		}
		
	}
