<?php
	
	namespace App\Http\Controllers;
	
	use App\Account;
	use App\Http\Requests\CreateTransactionRequest;
	use App\TransactionsContainer;
	
	class TransactionsController extends Controller
	{
		
		public function index()
		{
			$transactions = TransactionsContainer::paginate(20);
			
//			return $transactions[0]->transactions()->with('creditable','debitable')->get();
			return view('transactions.index',compact('transactions'));
		}
		
		public function create()
		{
			$accounts = Account::all();
			return view('transactions.create',compact('accounts'));
		}
		
		public function store(CreateTransactionRequest $request)
		{
			return $request->save();
			
		}
		
		public function show(TransactionsContainer $transaction)
		{
			$transactions = TransactionsContainer::where('id',$transaction->id)->paginate(10);
			return view('transactions.index',compact('transactions'));
		}
		//
	}
