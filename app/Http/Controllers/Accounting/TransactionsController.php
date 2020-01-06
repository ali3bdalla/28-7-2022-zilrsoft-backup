<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Account;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Transaction\CreateTransactionRequest;
	use App\TransactionsContainer;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\View\View;
	
	class TransactionsController extends Controller
	{
		
		/**
		 * @return Factory|View
		 */
		public function index()
		{
			$transactions = TransactionsContainer::orderBy('created_at','desc')->paginate(20);
			return view('accounting.transactions.index',compact('transactions'));
		}
		
		public function create()
		{
			$accounts = Account::all();
			return view('accounting.transactions.create',compact('accounts'));
		}
		
		/**
		 * @param CreateTransactionRequest $request
		 */
		public function store(CreateTransactionRequest $request)
		{
			return $request->save();
			
		}
		
		/**
		 * @param TransactionsContainer $transaction
		 *
		 * @return Factory|View
		 */
		public function show(TransactionsContainer $transaction)
		{
			$transactions = TransactionsContainer::where('id',$transaction->id)->paginate(10);
			return view('accounting.transactions.index',compact('transactions'));
		}
		//
	}
