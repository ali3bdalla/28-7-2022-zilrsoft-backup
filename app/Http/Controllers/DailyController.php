<?php
	
	namespace App\Http\Controllers;
	
	use App\Jobs\Accounting\Entity\ActivateEntityJob;
	use App\Models\Account;
	use App\Models\Manager;
	use App\Models\Payment;
	use App\Models\ResellerClosingAccount;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	
	class DailyController extends Controller
	{
		
		public function resellerClosingAccountsIndex()
		{
			$managerCloseAccountList = ResellerClosingAccount::where(
				[
					['creator_id', auth()->user()->id],
					['transaction_type', "close_account"],
				]
			)->orWhere(
				[
					['receiver_id', auth()->user()->id],
				]
			)->orderBy('id', 'desc')->paginate(15);
			
			return view('accounting.reseller_daily.account_close_list', compact('managerCloseAccountList'));
		}
		
		public function createResellerClosingAccount(Request $request)
		{
			$loggedUser = $request->user();
			$tempResellerAccount = Account::where(
				[
					['slug', 'temp_reseller_account'],
					['is_system_account', true],
				]
			)->first();
			
			$remainingAccountsBalanceAmount = $loggedUser->remaining_accounts_balance;
			$accountsClosedAt = $loggedUser->accounts_closed_at;
			
			
			if($accountsClosedAt != null) {
				$accountsClosedAt = Carbon::parse($accountsClosedAt);
				$inAmount = Payment::where(
					[
						['creator_id', $loggedUser->id],
					]
				)->where('created_at', '>=', $accountsClosedAt)->where(
					[
						['payment_type', 'receipt'],
						['invoice_id', '!=', null]
					]
				)->sum('amount');
				$outAmount = Payment::where(
					[
						['creator_id', $loggedUser->id],
					]
				)->where('created_at', '>=', $accountsClosedAt)->where(
					[
						['payment_type', 'payment'],
						['invoice_id', '!=', null]
					]
				)->sum('amount');
			} else {
				$inAmount = Payment::where(
					[
						['creator_id', $loggedUser->id],
					]
				)->where(
					[
						['payment_type', 'receipt'],
						['invoice_id', '!=', null]
					]
				)->sum('amount');
				
				$outAmount = Payment::where(
					[
						['creator_id', $loggedUser->id],
					]
				)->where(
					[
						['payment_type', 'payment'],
						['invoice_id', '!=', null]
					]
				)->sum('amount');
			}
			
			$gateways = $loggedUser->gateways()->get();
			
			
			return view('accounting.reseller_daily.account_close', compact('inAmount', 'loggedUser', 'accountsClosedAt', 'outAmount', 'gateways', 'remainingAccountsBalanceAmount'));
		}
		
		public function resellerAccountsTransactionsIndex()
		{
			$managerCloseAccountList = ResellerClosingAccount::where(
				[
					['creator_id', auth()->user()->id],
					['transaction_type', "transfer"],
				]
			)->orWhere(
				[
					['receiver_id', auth()->user()->id],
				]
			)->orderBy('id', 'desc')->paginate(15);
			
			return view('accounting.reseller_daily.tranfers_list', compact('managerCloseAccountList'));
		}
		
		public function createResellerAccountTransaction(Request $request)
		{
			$manager_gateways = $request->user()->gateways()->get();
			$managers = Manager::where('id', '!=', $request->user()->id)->with('gateways')->get();
			$gateways = [];
			foreach($managers as $manager) {
				foreach($manager->gateways as $gateway) {
					if($gateway->is_gateway) {
						$gateway['receiver_id'] = $manager['id'];
						$gateways[] = $gateway;
						
					}
					
				}
			}
			
			
			return view('accounting.reseller_daily.transfer_amounts', compact('gateways', 'manager_gateways'));
		}
		
		public function confirmResellerAccountTransaction(ResellerClosingAccount $transaction)
		{
			if($transaction->receiver_id == auth()->user()->id && $transaction->transaction_type == 'transfer') {
				$container = $transaction->container;
				dispatch(new ActivateEntityJob($container));
				$transaction->update(
					[
						'is_pending' => false,
					]
				);
				
				$creator = Manager::find($transaction->creator_id);
				if($creator) {
					$creator->update(
						[
							'remaining_accounts_balance' => $transaction->remaining_accounts_balance,
						]
					);
				}
				
			}
			
			return back();
		}
		
		
		public function deleteResellerAccountTransaction(ResellerClosingAccount  $transaction)
		{
			$transaction->delete();
			
			return back();
		}
	}
