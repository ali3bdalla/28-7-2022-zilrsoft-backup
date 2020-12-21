<?php
	
	namespace App\Http\Controllers\Api;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Account\FetchAccountsRequest;
	use App\Http\Requests\Account\StoreAccountRequest;
	use App\Http\Requests\Account\UpdateAccountRequest;
	use App\Models\Account;
	use Carbon\Carbon;
	use Exception;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\DB;
	
	class AccountController extends Controller
	{
		//
		
		public function index(FetchAccountsRequest $request)
		{
			return $request->getData();
		}
		
		public function children(Account $account)
		{
			$children = $account->children()->withCount('children')->orderBy('sorting_number', 'desc')->orderBy('id', 'ASC')->get();
			return $children;
		}
		
		public function store(StoreAccountRequest $request)
		{
			// return 1;
			return $request->store();
		}
		
		public function show(Account $account)
		{
		
		}
		
		public function update(UpdateAccountRequest $request, Account $account)
		{
		
		}
		
		
		/**
		 * @param Account $account
		 *
		 * @return bool|null
		 * @throws Exception
		 */
		public function destroy(Account $account)
		{
			$account->delete();
			if($account->parent) {
				$account->parent->updateHashMap();
			}
			
		}
		
		
		public function report(Account $account, Request $request)
		{
			$startDate = Carbon::parse($request->input('startDate'))->toDateString();
			$endDate = Carbon::parse($request->input('endDate'))->toDateString();
			
			if($startDate == $endDate) {
				$totalCredit = $account->transactions()->where('type', 'credit')->whereDate('created_at', $startDate)->sum('amount');
				$totalDebit = $account->transactions()->where('type', 'debit')->whereDate('created_at', $startDate)->sum('amount');
			} else {
				$totalCredit = $account->transactions()->where('type', 'credit')->whereBetween(DB::raw("DATE(created_at)"), [$startDate, $endDate])->sum('amount');
				$totalDebit = $account->transactions()->where('type', 'debit')->whereBetween(DB::raw("DATE(created_at)"), [$startDate, $endDate])->sum('amount');
				
			}

//			return [
//				$startDate->toDateString(),
//				$endDate->toDateString(),
//			];
////
//			$totalCredit = $account->snapshots()->whereBetween('created_at', [$startDate, $endDate])->sum('credit_amount');
//			$totalDebit = $account->snapshots()->whereBetween('created_at', [$startDate, $endDate])->sum('debit_amount');
//
			
			
			if($account->type == 'credit') {
				$balance = $totalCredit - $totalDebit;
			} else {
				$balance = $totalDebit - $totalCredit;
			}
			
			
			return [
				'total_credit' => $totalCredit,
				'total_debit' => $totalDebit,
				'amount' => $balance,
			];
		}
	}
