<?php
	
	namespace App\Http\Controllers\App\Web;
	
	
	use App\Models\Filter;
	use App\Models\FilterValues;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Filter\CreateFilterRequest;
	use App\Http\Requests\Accounting\Filter\DatatableRequest;
	use App\Http\Requests\Accounting\Filter\UpdateFilterRequest;
	use Exception;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Http\RedirectResponse;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	use Illuminate\Routing\Redirector;
	use Illuminate\View\View;
	
	
	class FilterController extends Controller
	{
		
		/**
		 * ItemController constructor.
		 */
		public function __construct()
		{
			$this->middleware(['permission:create filter|edit filter|view filter|delete filter']);
		}
		
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			
			
			return view('accounting.filters.index');
			//
		}
		
		/**
		 * @param DatatableRequest $request
		 *
		 * @return LengthAwarePaginator
		 */
		public function datatable(DatatableRequest $request)
		{
			$this->middleware(['permission:view filter']);
			return $request->data();
		}
		
		/**
		 * @return Factory|View
		 */
		public function create()
		{
			//
			$this->middleware(['permission:create filter']);
			return view('accounting.filters.create');
		}
		
		/**
		 * @param CreateFilterRequest $request
		 *
		 * @return RedirectResponse|Redirector
		 */
		public function store(CreateFilterRequest $request)
		{
			$data = $request->only('name', 'ar_name');
			$data['creator_id'] = auth()->user()->id;
			$filter = auth()->user()->organization->filters()->create($data);
			
			if(auth()->user()->can('edit filter'))
				return redirect(route('accounting.filters.edit', $filter->id));
			
			return redirect(route('accounting.filters.index'));
			//
		}
		
		/**
		 * @param Filter $filter
		 *
		 * @return Factory|View
		 */
		public function show(Filter $filter)
		{
			$this->middleware(['permission:view filter']);
			
			return view('accounting.filters.show');
			//
		}
		
		/**
		 * @param Filter $filter
		 *
		 * @return Factory|View
		 */
		public function edit(Filter $filter)
		{

//
			
			$this->middleware(['permission:edit filter']);
			return view('accounting.filters.edit', compact('filter'));
			//
		}
		
		/**
		 * @param Request $request
		 * @param Filter $filter
		 *
		 * @return Filter
		 */
		public function update(UpdateFilterRequest $request, Filter $filter)
		{
			
			
			$filter->forceFill($request->only('name', 'ar_name'))->save();
			
			return $filter;
			//
		}
		
		/**
		 * @param Filter $filter
		 *
		 * @throws Exception
		 */
		public function destroy(Filter $filter)
		{
			$this->middleware(['permission:delete filter']);
			
			
			$filter->delete();
			//
		}
		
		public function create_value(Request $request)
		{
			//
			$request->validate(
				[
					'name' => 'required|string|organization_unique:App\Models\FilterValues,name',
					'filter_id' => 'required|integer|exists:filters,id',
					'ar_name' => 'required|string|organization_unique:App\Models\FilterValues,ar_name'
				]
			);
			$data = $request->all();
			$data['organization_id'] = auth()->user()->organization_id;
			$value = auth()->user()->filters_values()->create(
				$data
			);
			
			$value['creator'] = auth()->user();
			$value['locale_name'] = $value['locale_name'];
			return $value;
			// return  $value->with('creator')->get();
			// return $value->with('creator')->get();
			
			
		}
		
		public function upate_value(Request $request)
		{
			
			$request->validate(
				[
					'name' => 'required|string',
					'filter_id' => 'required|integer|exists:filters,id',
					'id' => 'required|integer|exists:filter_values,id',
					'organization_id' => 'required|integer|exists:organizations,id',
					'ar_name' => 'required|string'
				]
			);
			
			
			$value = FilterValues::find($request->id);
			$value->name = $request->name;
			$value->ar_name = $request->ar_name;
			$value->save();
			
			
		}
		
		public function delete_value(FilterValues $value)
		{
			$value->delete();
		}
		
	}
