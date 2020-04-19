<?php
	
	
	namespace App\Components\Loader\Chart\Transaction;
	
	
	use App\Account;
	use App\Transaction;
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Http\Request;
	use Illuminate\Pagination\LengthAwarePaginator;
	
	class AccountTransactionsLoader
	{
		
		use TransactionsHelper,ResultsResponseHelper;
		
		/**
		 * @var Account
		 */
		private $account;
		private $db_rows;
		private $db_result_rows = [];
		
		private $total_debit_amount = 0;
		private $total_credit_amount = 0;
		private $is_credit_account = false;
		/**
		 * @var Request
		 */
		private $request;
		
		public function __construct(Account $account,Request $request)
		{
			$this->account = $account;
			$this->request = $request;
			
			$this->is_credit_account = $account->type == 'credit';
			$this->detectAccountType();
			$this->updateInitData();
			$this->createDBResultRows();
			
		}
		
		private function detectAccountType()
		{
			if ($this->account->slug == 'clients'){
				$this->loadClientsTransactions();
//				$users = User::where('is_client',true)->paginate(50);//$this->get_users_transactions
//				return view('accounting.charts.transactions.identity',compact('users','account'));
			}else if ($this->account->slug == 'vendors'){
				$this->loadVendorsTransactions();
//				$users = User::where('is_vendor',true)->paginate(50);
//				$users = $this->get_users_transactions('vendor_balance');
//				return view('accounting.charts.transactions.identity',compact('users','account'));
			}else if ($this->account->slug == 'stock'){
				$this->loadStockTransactions();
//				$items = $this->get_account_stock_item_transactions();
//				$items = $items['items'];
//				return view('accounting.charts.transactions.items',compact('items','account'));
			}else{
				$this->loadGlobalTransactions();
			}
			
		}
		
		private function updateInitData()
		{
			if ($this->isNotFirstPage()){
				$this->accountChildrenAccounts();
				$row = $this->db_rows[0];
				
				if ($row){
					$debit_transactions_query = Transaction::where([
						['id','<',$row->id],
						['debitable_type',get_class($this->account)],
						['debitable_id',$this->account->id]
					]);
					$this->total_debit_amount += $this->dispatchParams($debit_transactions_query)->sum("amount");
					
					$this->total_credit_amount += ($this->dispatchParams(Transaction::where([
							['id','<',$row->id],
							['creditable_type',get_class($this->account)],
							['creditable_id',$this->account->id]]
					)))->sum("amount");
				}
				
				
			}
			
			
		}
		
		private function isNotFirstPage()
		{
			return ($this->request->has('page') && $this->request->filled('page') && $this->request->input('page')
				> 1);
		}
		
		private function createDBResultRows()
		{
			if ($this->account->slug == 'clients'){
			
			}else if ($this->account->slug == 'vendors'){
			
			}else if ($this->account->slug == 'stock'){
			
			}else{
				$this->globalTransactionsResults();
			}
			
		}
		
		public function response()
		{
//			return
			return new LengthAwarePaginator(
				$this->db_result_rows,
				$this->db_rows->total(),
				$this->db_rows->perPage(),
				$this->db_rows->currentPage(),[
					'path' => \Request::url(),
					'query' => [
						'page' => $this->db_rows->currentPage()
					]
				]
			);
			
		}
		
		private function getPerPage()//(Builder $query
		{
			
//			if($this->request->has('startDate') && $this->request->filled('startDate'))
//			{
//				return $query->count();
//			}
//
			if ($this->request->has('itemsPerPage') && $this->request->filled('itemsPerPage'))
				$perPage = $this->request->input('itemsPerPage');
			else
				$perPage = 20;
			
			return $perPage;
		}
	}