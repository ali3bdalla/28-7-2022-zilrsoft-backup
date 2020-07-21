<?php
	
	
	namespace App\Components\Loader\Item\Transactions;
	
	
	use App\Item;
	use Illuminate\Http\Request;
	
	class ItemTransactionsLoader
	{
		use TransactionsHelper,DBHelper;
		
		protected $item_sales_total_profits = 0;
		protected $item_current_cost = 0;
		protected $item_current_stock_qty = 0;
		protected $item_current_stock_amount = 0;
		protected $transactions_discount_details = [];
		protected $transactions_expenses_details = [];
		protected $transaction_stock_amount = 0;
		protected $transaction_stock_cost = 0;
		protected $transaction_description = "";
		protected $transaction_profits = 0;
		
		private $item;
		private $params = [];
		private $db_rows = [];
		private $result_pagination = [];
		private $db_query;
		private $results = [];
		private $other_results = [];
		private $request = null;
		
		private $init_transaction = null;
		private $start_at_date = null;
		
		public function __construct(Item $item,Request $request)
		{
			$this->item = $item;
			$this->request = $request;
			$this->resetTransactionVaraiables();
		}
		
		public function run()
		{
			$this->runRowsFetcher();
			
			$this->other_results['total_sales_profits'] = $this->item_sales_total_profits;
			$this->other_results['total_stock_amount'] = $this->item_current_stock_amount;
			$this->other_results['total_stock_qty'] = $this->item_current_stock_qty;
			$this->other_results['current_stock_item_cost'] = $this->item_current_cost;
			
			if ($this->isFreshQueryWithoutFilters() && $this->isLastPage())
				$this->updateItemStockData();
			
			return $this->response();
		}



		private function isLastPage()
        {
            return false;
        }
		private function runRowsFetcher()
		{
			$this->sendDatabaseQuery();
			$this->validateFiltersActivatedToUpdateInitItemStockData();
			$this->results = $this->fetchTransactionsResults();
			
			
		}
		
		private function validateFiltersActivatedToUpdateInitItemStockData()
		{
			if ($this->start_at_date != null){
				$this->init_transaction =
					$this->item
						->history()
						->where('invoice_type','!=','quotation')
						->whereDate('created_at','<',$this->start_at_date)
						->orderBy('id','desc')->first();
				
				
			}
			
			if ($this->request->has('page') && $this->request->filled('page') && $this->request->input('page') > 1){
				$page_t = $this->request->input("page") - 1;
				$results_t = $this->item->history()->where('invoice_type','!=','quotation')->paginate
				($this->getPerPage(),
					['*'],'page',$page_t);
				$this->init_transaction = $results_t[count($results_t) - 1];
			}
			
			
			if ($this->init_transaction != null){
				$this->item_current_cost =
					$this->init_transaction->cost;
				$this->item_current_stock_qty =
					$this->init_transaction->item_available_qty;
				$this->item_current_stock_amount =
					$this->init_transaction->item_current_stock_qty * $this->item_current_cost;
			}
		}
		
		private function isFreshQueryWithoutFilters():bool
		{
			return $this->start_at_date == null;
		}
		
		private function response()
		{
			$this->result_pagination->data = $this->results;
			$response_t= json_decode(json_encode($this->result_pagination),true);
			$response_t['totals'] = $this->other_results;
			return $response_t;
//			$this->results['totals'] =  $this->other_results;

//			$this->result_pagination['totals_amounts'] = $this->results;
//			$this->result_pagination->total_profits= $this->item_sales_total_profits;
			return $this->result_pagination;
//			return $this->other_results;
//			$paginate = new Pagination($this->results,$this->request);
//			return view('old.welcome');
//			return $paginate->paginate();
		
		
		}
		
	}