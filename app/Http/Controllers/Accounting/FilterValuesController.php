<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Filter;
	use App\FilterValues;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Filter\CreateFilterValueRequest;
	use App\Http\Requests\Accounting\Filter\DatatableRequest;
	use App\Http\Requests\Accounting\Filter\UpdateFilterValueRequest;
	use App\Http\Requests\Accounting\Filter\ValuesDataTableRequest;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class FilterValuesController extends Controller
	{
		
		/**
		 * ItemController constructor.
		 */
		public function __construct()
		{
			$this->middleware(['permission:create filter|edit filter|view filter|delete filter']);
		}
		
		/**
		 * @param DatatableRequest $request
		 *
		 * @return LengthAwarePaginator
		 */
		public function datatable(ValuesDataTableRequest $request,Filter $filter)
		{
			return $request->data($filter);
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			$this->middleware(['permission:edit filter|create filter']);
			//
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function store(CreateFilterValueRequest $request)
		{
			$this->middleware(['permission:edit filter|create filter']);
			
			return $request->save();
			//
		}
		
		
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param FilterValues $filterValues
		 *
		 * @return Response
		 */
		public function update(UpdateFilterValueRequest $request,FilterValues $filterValue)
		{
			$this->middleware(['permission:edit filter|create filter']);
			
			$data = $request->only('filter_id','name','ar_name');
			$filterValue->update($data);
			return $filterValue->fresh();
			
		}
		
		/**
		 * Remove the specified resource from storage.
		 *
		 * @param FilterValues $filterValues
		 *
		 * @return Response
		 */
		public function destroy(FilterValues $filterValue)
		{
			$this->middleware(['edit filter']);
			
			$filterValue->delete();
			//
		}
	}
