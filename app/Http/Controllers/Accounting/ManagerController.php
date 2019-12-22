<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Branch;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Manager\CreateManagerRequest;
	use App\Http\Requests\Accounting\Manager\DatatableRequest;
	use App\Manager;
	use Illuminate\Contracts\Pagination\LengthAwarePaginator;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class ManagerController extends Controller
	{
		public function __construct()
		{
			$this->middleware(['permission:manage managers']);
		}
		
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			$branches = Branch::with('departments')->get();
			return view('accounting.managers.index',compact('branches'));
			//
		}
		
		/**
		 * @param DatatableRequest $request
		 *
		 * @return LengthAwarePaginator
		 */
		public function datatable(DatatableRequest $request)
		{
			return $request->data();
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			$branches = Branch::with('departments')->get();
			return view('accounting.managers.create',compact('branches'));
			//
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function store(CreateManagerRequest $request)
		{
			return $request->save();
			//
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param Manager $manager
		 *
		 * @return Response
		 */
		public function show(Manager $manager)
		{
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param Manager $manager
		 *
		 * @return Response
		 */
		public function edit(Manager $manager)
		{
			//
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param Manager $manager
		 *
		 * @return Response
		 */
		public function update(Request $request,Manager $manager)
		{
			//
		}
		
		/**
		 * Remove the specified resource from storage.
		 *
		 * @param Manager $manager
		 *
		 * @return Response
		 */
		public function destroy(Manager $manager)
		{
			//
		}
	}
