<?php
	
	namespace App\Http\Controllers;
	
	use App\Account;
	use App\Http\Requests\CreateTransactionRequest;
	use App\TransactionsContainer;
	use Illuminate\Http\Request;
	
	class TransactionsController extends Controller
	{
		
		public function index()
		{
			$transactions = TransactionsContainer::all();
			
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
		//
	}
