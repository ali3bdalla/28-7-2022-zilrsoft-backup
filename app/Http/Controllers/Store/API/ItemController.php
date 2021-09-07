<?php
	
	namespace App\Http\Controllers\Store\API;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Web\Item\FetchItemsGroupByCategoryRequest;
	use App\Http\Requests\Web\Item\FetchItemsUsingFiltersRequest;
	
	class ItemController extends Controller
	{
		
		public function usingFilters(FetchItemsUsingFiltersRequest $request)
		{
			return $request->getData();
		}
		
		public function index(FetchItemsGroupByCategoryRequest $fetchItemsGroupByCategoryRequest)
		{
			return $fetchItemsGroupByCategoryRequest->getData();
		}
	}
