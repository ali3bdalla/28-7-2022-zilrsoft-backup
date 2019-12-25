<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Category;
	use App\CategoryFilters;
	use App\Filter;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Item\FindItemsRequest;
	use App\Http\Requests\Accounting\Item\ValidatePurchaseSerialsRequest;
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Database\Eloquent\Collection;
	use Illuminate\Http\Request;
	use Spatie\Permission\Models\Role;
	
	class ProviderController extends Controller
	{
		/**
		 * @param Category $category
		 *
		 * @return mixed
		 */
		public function categories_filters(Request $request)
		{
			$categories_ids = $request->input("categories_ids");
//			return $categories_ids;
			if (empty($categories_ids))
				return [];
			
			
			
			$filters = CategoryFilters::whereIn('category_id',$categories_ids)
				->with('filter.values')->orderBy('id','asc')->get();
			
			$result = [];
			foreach ($filters as $filter)
			{
				$result[] = $filter['filter'];
			}
			
			return $result;
		}
		
		/**
		 * @return Builder[]|Collection
		 */
		public function roles_permissions()
		{
			$this->middleware(['permissions:manage managers']);
			
			return Role::with('permissions')->get();
			
		}
		
		/**
		 * @param FindItemsRequest $request
		 *
		 * @return mixed
		 */
		public function query_find_items(FindItemsRequest $request)
		{
			return $request->results();
		}
		
		public function query_validate_purchase_serial(ValidatePurchaseSerialsRequest $request)
		{
			return $request->good();
		}
		
		public function query_validate_sale_serial(ValidatePurchaseSerialsRequest $request)
		{
			return $request->good();
		}
		
		public function query_validate_return_sale_serial(ValidatePurchaseSerialsRequest $request)
		{
			return $request->good();
		}
		
		public function query_validate_return_purchase_serial(ValidatePurchaseSerialsRequest $request)
		{
			return $request->good();
		}
		
		//
	}
