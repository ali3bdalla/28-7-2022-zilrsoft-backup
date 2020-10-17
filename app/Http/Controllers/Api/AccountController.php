<?php
	
	namespace App\Http\Controllers\Api;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Account\FetchAccountsRequest;
	use App\Http\Requests\Account\StoreAccountRequest;
	use App\Http\Requests\Account\UpdateAccountRequest;
	use App\Models\Account;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	
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
			return $request->store();
		}
		
		public function show(Account $account)
		{
		
		}
		
		public function update(UpdateAccountRequest $request, Account $account)
		{
		
		}
		
		
		public function report(Account $account,Request  $request)
		{
			$startDate = Carbon::parse($request->startDate);
			$endDate = Carbon::parse($request->endDate);
			$totalCredit = $account->transactions()->where('type','credit')->whereBetween('created_at',[$startDate,$endDate])->sum('amount');
			$totalDebit = $account->transactions()->where('type','debit')->whereBetween('created_at',[$startDate,$endDate])->sum('amount');
			$balance = 0;
			if($account->type == 'credit')
			{
				$balance = $totalCredit - $totalDebit;
			}else
			{
				$balance = $totalDebit - $totalCredit;
			}
			
			return [
				'total_credit' => $totalCredit,
				'total_debit' => $totalDebit,
				'amount' => $balance,
			];
		}
	}
