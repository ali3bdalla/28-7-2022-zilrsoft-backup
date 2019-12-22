<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Category;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Item\CreateItemRequest;
	use App\Http\Requests\Accounting\Item\DatatableRequest;
	use App\Http\Requests\Accounting\Item\UpdateItemRequest;
	use App\Item;
	use App\User;
	use Illuminate\Contracts\Pagination\LengthAwarePaginator;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\View\View;
	use Symfony\Component\HttpFoundation\Request;
	
	class ItemController extends Controller
	{
		
		/**
		 * ItemController constructor.
		 */
		public function __construct()
		{
			$this->middleware(['permission:create item|edit item|view item|delete item|view item transactions']);
		}
		
		/**
		 * @return Factory|View
		 */
		public function index()
		{
			$this->middleware("permission:view item");
			
			$categories = Category::all();
			
			return view('accounting.items.index',compact('categories'));
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
		 * @return Factory|View
		 */
		public function create()
		{
			$this->middleware("permission:create item");
			$isClone = false;
			$chats = Category::mainOnly()->get();
			$categories = [];
			foreach ($chats as $category){
				$category['children'] = Category::getAllParentNestedChildren($category);
				$categories[] = $category;
			}
			$vendors = User::where('is_vendor',true)->get();
			
			return view('accounting.items.create',compact('categories','isClone','vendors'));
			
		}
		
		/**
		 * @param Request $request
		 *
		 * @return mixed
		 */
		public function validate_barcode(Request $request)
		{
			$this->middleware("permission:create item");
			$request->validate([
				'barcode' => 'required|string|min:4'
			]);
			
			$result = Item::where('barcode',$request->barcode)->get();
			
			return $result;
		}
		
		/**
		 * @param CreateItemRequest $request
		 *
		 * @return mixed
		 */
		public function store(CreateItemRequest $request)
		{
			return $request->save();
		}
		
		/**
		 * @param Item $item
		 *
		 * @return Factory|View
		 */
		public function edit(Item $item)
		{
			$this->middleware("permission:edit item");
			$isClone = 0;
			$chats = Category::mainOnly()->get();
			$categories = [];
			foreach ($chats as $category){
				$category['children'] = Category::getAllParentNestedChildren($category);
				$categories[] = $category;
			}
			$vendors = User::where('is_vendor',true)->get();
			return view('accounting.items.edit',compact('categories','isClone','vendors','item'));
			
			
		}
		
		/**
		 * @param Item $item
		 *
		 * @return Factory|View
		 */
		public function clone(Item $item)
		{
			$this->middleware("permission:edit item");
			$isClone = 1;
			$chats = Category::mainOnly()->get();
			$categories = [];
			foreach ($chats as $category){
				$category['children'] = Category::getAllParentNestedChildren($category);
				$categories[] = $category;
			}
			$vendors = User::where('is_vendor',true)->get();
			return view('accounting.items.edit',compact('categories','isClone','vendors','item'));
			
			
		}
		
		/**
		 * @param UpdateItemRequest $request
		 * @param Item $item
		 *
		 * @return mixed
		 */
		public function update(UpdateItemRequest $request,Item $item)
		{
			return $request->save($item);
			//
		}
		
		public function transactions()
		{
			$this->middleware(['permission:view item transactions']);
			
		}
		
	}
