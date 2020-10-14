<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accounting\Voucher\CreateVoucherRequest;
use App\Http\Requests\Accounting\Voucher\DatatableRequest;
use App\Models\Manager;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class VoucherController extends Controller
{
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
			$currentAssetsAccount = Account::where('slug','current_assets')->orderBy('id')->first();

			$allCurrentAssetsAccounts = $currentAssetsAccount->getChildrenIncludeMe();

			// return $allCurrentAssetsAccounts;
			// return $current_assets_account;
			
			$accounts = auth()->user()->gateways()->get();
			// $accounts = Account::find($allCurrentAssetsAccounts);
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
		
		// /**
		//  * @param CreateVoucherRequest $request
		//  *
		//  * @return string
		//  */
		// public function store(CreateVoucherRequest $request)
		// {
			
		// 	return $request->save();
		// }
}