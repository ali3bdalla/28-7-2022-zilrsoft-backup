<?php
	
	namespace App\Http\Controllers\App\Web;
	
	use App\Models\Expense;
	use App\Models\Filter;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Expense\CreateExpenseRequest;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class ExpenseController extends Controller
	{
		
		public function __construct()
		{
			$this->middleware(['permission: manage expenses']);
		}
		
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			$expenses = Expense::all();
			return view('accounting.expenses.index',compact('expenses'));
			//
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			//
			return view('accounting.expenses.create');
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function store(CreateExpenseRequest $request)
		{
//
//			return 1;
			$data = $request->only('name','ar_name');
			$data['creator_id'] = auth()->user()->id;
			
			auth()->user()->organization->expenses()->create($data);
			
			return redirect(route('accounting.expenses.index'));
			//
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param Filter $filter
		 *
		 * @return Response
		 */
		public function show(Filter $filter)
		{
			
			return view('expenses.show');
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param Filter $filter
		 *
		 * @return Response
		 */
		public function edit(Filter $filter)
		{

//        return  $filter->values()->orderBy('id','desc')->get();
			
			// return;
			return view('expenses.edit',compact('filter'));
			//
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param Filter $filter
		 *
		 * @return Response
		 */
		public function update(Request $request,Filter $filter)
		{
			
			
			$request->validate([
				'name' => 'required|string',
				'ar_name' => 'required|string'
			]);
			
			
			$filter->forceFill($request->only('name','ar_name'))->save();
			
			return $filter;
			//
		}
		
		public function destroy(Expense $expense)
		{
			$expense->delete();
			
			return back();
		}
		
	}
