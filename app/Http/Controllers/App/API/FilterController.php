<?php
	
	namespace App\Http\Controllers\App\API;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Filter\StoreFilterRequest;
	use App\Http\Requests\Filter\UpdateFilterRequest;
	use App\Models\Filter;
	use Illuminate\Http\Request;
	
	class FilterController extends Controller
	{
		public function store(StoreFilterRequest $request)
		{
			return $request->store();
		}
		
		
		public function update(UpdateFilterRequest $request, Filter $filter)
		{
			return $request->update($filter);
		}
		//
	}
