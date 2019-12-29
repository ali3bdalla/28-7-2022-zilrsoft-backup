<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Category;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Item\ActivateItemsRequest;
	use App\Http\Requests\Accounting\Item\CreateItemRequest;
	use App\Http\Requests\Accounting\Item\DatatableRequest;
	use App\Http\Requests\Accounting\Item\UpdateItemRequest;
	use App\Invoice;
	use App\Item;
	use App\ItemSerials;
	use App\Manager;
	use App\User;
	use Illuminate\Contracts\Pagination\LengthAwarePaginator;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Support\Collection;
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
			$creators = Manager::all();
			
			return view('accounting.items.index',compact('categories','creators'));
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
		 * @param Invoice $saleInvoice
		 */
		public function show(Invoice $saleInvoice)
		{
		
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
		
		/**
		 *
		 */
		public function transactions(Item $item)
		{
			return view('accounting.items.transactions',compact('item'));
			
		}
		
		/**
		 * @param Item $item
		 * @param Request $request
		 *
		 * @return array|Collection
		 */
		public function transactions_datatable(Item $item,Request $request)
		{
//			return  1;
			
			if ($request->has('startDate') && $request->filled('startDate') && $request->has('endDate') &&
				$request->filled('endDate')){
				return $item->stockMovement(['startDate' => $request->startDate,'endDate' => $request->endDate]);
			}
//			return  $item;
			return collect($item->stockMovement());
			
		}
		
		/**
		 * @param ActivateItemsRequest $request
		 */
		public function activate_items(ActivateItemsRequest $request)
		{
			return $request->activate();
		}
		
		/**
		 * @param Item $item
		 *
		 * @return Factory|View
		 */
		public function view_serials(Item $item)
		{
			
			$serials = $item->serials()->paginate(20);
			return view('accounting.items.view_serials',compact('item','serials'));
		}
		
		/**
		 * @return Factory|View
		 */
		public function serial_activities()
		{
			return view('accounting.items.serial.index');
		}
		
		/**
		 * @param Request $request
		 *
		 * @return Factory|View
		 */
		public function show_serial_activities(Request $request)
		{
			$request->validate([
				'serial' => 'required|exists:item_serials,serial'
			]);
			
			$serial = ItemSerials::where('serial',$request->serial)->first();
			return view('accounting.items.serial.show',compact('serial'));
		}
		
		public function barcode()
		{
			return view('accounting.items.barcode.index');
		}
		
		public function show_item_barcode(Request $request)
		{
			
			
			$request->validate([
				'barcode' => 'required|exists:items,barcode'
			]);
			
			$items = Item::where('barcode',$request->barcode)->first();
			return view('accounting.items.barcode.show',compact('items'));
		}
		
	}
