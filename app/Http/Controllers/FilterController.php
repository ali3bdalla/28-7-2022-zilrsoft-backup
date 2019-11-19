<?php
	
	namespace App\Http\Controllers;
	
	
	use App\Filter;
	use App\FilterValues;
	use App\Http\Requests\CreateFilterRequest;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;

// use App\Http\Requests\CreateFilterValueRequest;
	
	
	class FilterController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			
			$filters = Filter::paginate(40);
			return view('filters.index',compact('filters'));
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
			return view('filters.create');
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param Request $request
		 *
		 * @return Response
		 */
		public function store(CreateFilterRequest $request)
		{
			
			$data = $request->only('name','ar_name');
			$data['creator_id'] = auth()->user()->id;
			$filter = auth()->user()->organization->filters()->create($data);
			
			return redirect(route('management.filters.edit',$filter->id));
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
			
			return view('filters.show');
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
			return view('filters.edit',compact('filter'));
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
		
		/**
		 * Remove the specified resource from storage.
		 *
		 * @param Filter $filter
		 *
		 * @return Response
		 */
		public function destroy(Filter $filter)
		{
			//
		}
		
		public function create_value(Request $request)
		{
			//
			$request->validate([
				'name' => 'required|string|unique:filter_values,name',
				'filter_id' => 'required|integer|exists:filters,id',
				'ar_name' => 'required|string|unique:filter_values,ar_name'
			]);
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
			
			$request->validate([
				'name' => 'required|string',
				'filter_id' => 'required|integer|exists:filters,id',
				'id' => 'required|integer|exists:filter_values,id',
				'organization_id' => 'required|integer|exists:organizations,id',
				'ar_name' => 'required|string'
			]);
			
			
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
