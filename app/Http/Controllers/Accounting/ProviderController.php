<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Category;
	use App\CategoryFilters;
	use App\Filter;
	use App\Http\Controllers\Controller;
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
			
			
			$filters = CategoryFilters::whereIn('category_id',$categories_ids)->pluck('filter_id')->toArray();
			
			
			return Filter::whereIn('id',$filters)->with('values')->get();
		}
		
		public function roles_permissions()
		{
			$this->middleware(['permissions:manage managers']);
			
			return Role::with('permissions')->get();
			
		}
		//
	}
