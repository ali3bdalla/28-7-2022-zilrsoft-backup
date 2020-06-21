<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Account;
	use App\Accounting\TransactionAccounting;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Charts\PeriodCloseAccoundRequest;
	use App\Http\Requests\Accounting\ResellerDaily\TransferAmountsRequest;
	use App\Manager;
	use App\ManagerPrivateTransactions;
	use App\Transaction;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Http\Request;
	use Illuminate\View\View;
	
	class ResellerDailyTransactions extends Controller
	{
		
		use TransactionAccounting;

		
		public function dailyShutdownList()
		{
			$managerCloseAccountList = ManagerPrivateTransactions::where([
				['creator_id',auth()->user()->id],
				['transaction_type',"close_account"],
			
			])->orWhere([
				['receiver_id',auth()->user()->id]
			])->orderBy('id','desc')->paginate(15);
			
			return view('accounting.reseller_daily.account_close_list',compact('managerCloseAccountList'));
		}


		public function transferList()
		{
			$managerCloseAccountList = ManagerPrivateTransactions::where([
				['creator_id',auth()->user()->id],
				['transaction_type',"transfer"],
			])->orWhere([
				['receiver_id',auth()->user()->id]
			])->orderBy('id','desc')->paginate(15);
			
			return view('accounting.reseller_daily.tranfers_list',compact('managerCloseAccountList'));
		}
		
		/**
		 * @param Request $request
		 *
		 * @return Factory|View
		 */
		public function dailyShutdownShowForm(Request $request)
		{
			$lastRemainingTransferAmount = $this->toGetLastManagerTransferRemainingAmount();
			$periodSalesAmount = $request->user()->dailyTransactionsAmount();
			$gateways = $request->user()->gateways()->get();
			return view('accounting.reseller_daily.account_close',compact('periodSalesAmount','gateways','lastRemainingTransferAmount'));
		}
		

		public function dailyShutdownSubmitForm(PeriodCloseAccoundRequest $request)
		{
			return $request->save();
		}
		

		public function transferShowForm(Request $request)
		{
			$manager_gateways = $request->user()->gateways()->where('is_gateway',true)->get();
			$managers = Manager::where('id','!=',$request->user()->id)->with('gateways')->get();
			$gateways = [];
			foreach ($managers as $manager){
				foreach ($manager->gateways as $gateway){
					if ($gateway->is_gateway){
						$gateway['receiver_id'] = $manager['id'];
						$gateways[] = $gateway;
						
					}
					
				}
			}
			return view('accounting.reseller_daily.transfer_amounts',compact('gateways','manager_gateways'));
		}


		public function transferSubmitForm(TransferAmountsRequest $request)
		{
			return $request->save();
		}


		public function deleteTransferTransactions(ManagerPrivateTransactions $transaction)
		{
			if ($transaction->receiver_id == auth()->user()->id && $transaction->transaction_type == 'transfer'){
				$container = $transaction->container;
				$container->transactions()->withoutGlobalScope('pendingTransactionScope')->delete();
				$container->delete();
				$transaction->delete();
			}
			
			return back();
		}
		
		public function confirmTransferTransactions(ManagerPrivateTransactions $transaction)
		{
			if ($transaction->receiver_id == auth()->user()->id && $transaction->transaction_type == 'transfer'){
				$container = $transaction->container;
				$container->transactions()->withoutGlobalScope('pendingTransactionScope')->update([
					'is_pending' => false
				]);
				$container->update([
					'is_pending' => false
				]);
				$transaction->update([
					'is_pending' => false
				]);
			}
//
			return back();
		}
	}
