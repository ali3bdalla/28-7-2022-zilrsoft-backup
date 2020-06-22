<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Account;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Voucher\CreateVoucherRequest;
	use App\Http\Requests\Accounting\Voucher\DatatableRequest;
	use App\Manager;
	use App\Payment;
	use App\User;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\View\View;
	use Symfony\Component\HttpFoundation\Request;
	
	class VoucherController extends Controller
	{
		
		/**
		 * VoucherController constructor.
		 */
		public function __construct()
		{
			$this->middleware(['permission:create voucher|edit voucher|view voucher|delete voucher']);
		}
		
		/**
		 * @return Factory|View
		 */
		public function index()
		{
			$identities = User::all();
			$creators = Manager::all();
			return view('accounting.vouchers.index',compact('creators','identities'));
		}
		
		/**
		 * @param DatatableRequest $request
		 *
		 * @return mixed
		 */
		public function datatable(DatatableRequest $request)
		{
			return $request->data();
		}
		
		/**
		 * @param Payment $payment
		 *
		 * @return Factory|View
		 */
		public function show(Payment $voucher)
		{
			$payment = $voucher;
			return view('accounting.vouchers.show',compact('payment'));
		}
		
		public function create(Request $request)
		{
			$current_assets_account = auth()->user()->toGetManagerAccount('current_assets');
			$accots = Account::where([['parent_id',$current_assets_account->id]])->get();
			$accounts = [];
			foreach ($accots as $account){
				$account['children'] = Account::getAllParentNestedChildren($account);
				$accounts[] = $account;
			}
			
			$voucher_types = config('global.voucher_types');
			if ($request->input('voucher_type') == 'receipt'){
				$voucher_type = 'receipt';
				$users = User::where([
					['is_client',true],
					['is_system_user',false],
				])->with('gateways.bank')->get();
			}else{
				$voucher_type = 'payment';
				$users = User::where([
					['is_vendor',true],
					['is_system_user',false],
				])->with('gateways.bank')->get();
			}

//			return  $accounts;
			return view('accounting.vouchers.create',compact('accounts','users','voucher_types','voucher_type'));
		}
		
		/**
		 * @param CreateVoucherRequest $request
		 *
		 * @return string
		 */
		public function store(CreateVoucherRequest $request)
		{
			
			return $request->save();
		}
		
	}
