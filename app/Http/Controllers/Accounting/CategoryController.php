<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Models\Category;
	use App\Models\Filter;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Category\CreateCategoryRequest;
	use App\Http\Requests\Accounting\Category\UpdateCategoryRequest;
	use Exception;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Http\Response;
	use Illuminate\View\View;
	use Symfony\Component\HttpFoundation\Request;
	
	class CategoryController extends Controller
	{
		
		/**
		 * ItemController constructor.
		 */
		public function __construct()
		{
			$this->middleware(['permission:create category|edit category|view category|delete category']);
		}
		
		/**
		 * @return Factory|View
		 */
		public function index()
		{
			$this->middleware(['permission:view category']);
			
			$chats = Category::where('parent_id',0)->get();

			// return $chats;
			$categories = [];
			foreach ($chats as $category){
				$category['children'] = Category::getAllParentNestedChildren($category);
				$categories[] = $category;
			}
			return view('accounting.categories.index',compact('categories'));
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create(Request $request)
		{
			$this->middleware(['permission:create category']);
			$request->validate([
				'parent_id' => 'nullable|organization_exists:App\Models\Category,id|integer'
			]);
			if (isset($request->parent_id)){
				$parent_id = $request->parent_id;
			}else{
				$parent_id = 0;
			}
			$categories = Category::all();
			
			return view('accounting.categories.create',compact('categories','parent_id'));
		}
		
		public function store(CreateCategoryRequest $request)
		{
            return $request->save();

		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function edit(Category $category)
		{
			$this->middleware(['permission:edit category']);
			
			$categories = Category::all();

			$parent_id = 0;
			return view('accounting.categories.edit',compact('categories','parent_id','category'));
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function clone(Category $category)
		{
			$this->middleware(['permission:create category']);
			
			$categories = Category::all();
			
			$parent_id = $category->parent_id;
			return view('accounting.categories.clone',compact('categories','parent_id','category'));
		}
		
		public function update(UpdateCategoryRequest $request,Category $category)
		{
			$this->middleware(['permission:edit category']);

			return $request->update($category);
			//
		}
		
		/**
		 * @param Category $category
		 *
		 * @throws Exception
		 */
		public function destroy(Category $category)
		{
			$this->middleware(['permission:delete category']);
			$category->delete();
		}
		
		/**
		 * @param Category $category
		 *
		 * @return Factory|View
		 */
		public function filters(Category $category)
		{
			$this->middleware(['permission:edit category']);
			$cat_filters = [];
			$cilfters = $category->filters;
			foreach ($cilfters as $key => $value){
				$cat_filters[] = collect($value)->forget('pivot');
			}
			$all_filters = Filter::whereNotIn("id",collect($cat_filters)->pluck('id')->toArray())->get();
			return view('accounting.categories.filters',compact('cat_filters','all_filters','category'));
		}
		
		/**
		 * @param Category $category
		 * @param Request $request
		 */
		public function update_filters(Category $category,Request $request)
		{
			$this->middleware(['permission:edit category']);
			
			$ids = collect($request->all())->pluck('id');
			
			$category->filters()->detach();
			$category->filters()->attach($ids,
				[
					'organization_id' => auth()->user()->organization_id,
					'creator_id' => auth()->user()->id,
					'sorting' => 0
				]
			);
			
			
		}
		
	}
