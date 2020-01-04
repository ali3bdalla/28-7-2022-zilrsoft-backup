<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Account;
	use App\Category;
	use App\CategoryFilters;
	use App\Core\MathCore;
	use App\Filter;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Item\FindItemsRequest;
	use App\Http\Requests\Accounting\Item\ValidatePurchaseSerialsRequest;
	use App\Http\Requests\Accounting\Item\ValidateSerialRequest;
	use App\Item;
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
			foreach ($filters as $filter){
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
		 * @param Request $request
		 *
		 * @return mixed
		 */
		public function get_gateways_like_to_manager_name(Request $request)
		{
			$query = Account::where('slug','gateway');
			
			if ($request->has('name') && $request->filled('name')){
				$query = $query->where('name','LIKE','%'.$request->input('name').'%');
			}
			
			if ($request->has('ar_name') && $request->filled('ar_name')){
				$query = $query->where('ar_name','LIKE','%'.$request->input('ar_name').'%');
			}
			return $query->get();
			
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
		
		/**
		 * @param ValidateSerialRequest $request
		 */
		public function query_validate_purchase_serial(ValidateSerialRequest $request)
		{
			
			return $request->purchase();
		}
		
		/**
		 * @param ValidateSerialRequest $request
		 *
		 * @return mixed
		 */
		public function query_validate_sale_serial(ValidateSerialRequest $request)
		{
//
			return $request->sale();
		}
		
		/**
		 * @param ValidateSerialRequest $request
		 */
		public function query_validate_return_sale_serial(ValidateSerialRequest $request)
		{
//			return $request->good();
		}
		
		/**
		 * @param ValidateSerialRequest $request
		 */
		public function query_validate_return_purchase_serial(ValidateSerialRequest $request)
		{
//			return $request->good();
		}
		
		public function get_kit_amounts(Item $kit,Request $request)
		{
			$children = $kit->items;
			
			$qty = $request->has('qty') && $request->filled("qty") && is_numeric($request->input('qty')) ?
				$request->input("qty") : 1;
			
			$result['total'] = 0;
			$result['subtotal'] = 0;
			$result['tax'] = 0;
			$result['discount'] = 0;
			$result['net'] = 0;
			
			
			$mathCore = new MathCore();
			
			foreach ($children as $item){
				
				$result['total'] = $result['total'] + ($item['total'] * $qty);
				$result['subtotal'] = $result['subtotal'] +  ($item['subtotal'] * $qty);
				$result['tax'] = $result['tax'] +  ($item['tax'] * $qty);
				$result['discount'] = $result['discount'] +  ($item['discount'] * $qty);
				$result['net'] = $result['net'] +  ($item['net'] * $qty);
				
				
			}
			
			return $result;
		}
	}
