<?php
	
	namespace App\Http\Controllers;
	
	use App\Category;
	use App\Http\Requests\CheckItemSerialRequest;
	use App\Http\Requests\CreateItemRequest;
	use App\Http\Requests\ItemCheckRequest;
	use App\Http\Requests\UpdateItemRequest;
	use App\Item;
	use App\ItemSerials;
	use App\Manager;
	use App\User;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	
	class ItemController extends Controller
	{
		
		public function index()
		{
			
			
			$creators = Manager::all();
			$loadType = 'active';
			return view('accounting.items.index',compact('loadType','creators'));
		}
		
		public function pending()
		{
			
			$loadType = 'pending';
			$creators = Manager::all();
			
			return view('items.index2',compact('loadType','creators'));
		}
		
		public function show2(Item $item)
		{
			return view('items.movement',compact('item'));
		}
		
		public function flow(Item $item)
		{
			$items = $item->history()->get();
			$pitem = $item;
			return view('items.flow',compact('items','pitem'));
		}
		
		public function datatable_list(Request $request)
		{
			
			
			$query = Item::with('creator');
			
			
			if ($request->has('barcode') && $request->filled('barcode')){
				$query = $query->where('barcode','LIKE','%'.$request->barcode.'%');
			}
			
			if ($request->has('creators') && $request->filled('creators')){
				$query = $query->whereIn('creator_id',$request->creators);
			}
			
			
			if ($request->has('startDate') && $request->filled('startDate') && $request->has('endDate') &&
				$request->filled
				('endDate')){
				
				$_startDate = new Carbon($request->startDate);
				$_endDate = new Carbon($request->endDate);
				$query = $query->whereBetween('created_at',[
					$_startDate->toDateTimeString(),
					$_endDate->toDateTimeString()
				]);
			}
			
			
			if ($request->has('name') && $request->filled('name')){
				$query = $query->where('name','LIKE','%'.$request->name.'%')->orWhere('ar_name','LIKE','%'.$request->name.'%');
			}
			
			
			if ($request->has('id') && $request->filled('id')){
				$query = $query->where('id',$request->id);
			}
			
			
			if ($request->has('qty') && $request->filled('qty')){
				$query = $query->where('available_qty',$request->qty);
			}
			
			if ($request->has('date') && $request->filled('date')){
				$query = $query->where('date','LIKE','%'.$request->date.'%');
			}
			
			
			if ($request->has('price') && $request->filled('price')){
				$query = $query->where('price',$request->price);
			}
			
			
			if ($request->has('price_with_tax') && $request->filled('price_with_tax')){
				$query = $query->where('price_with_tax',$request->price_with_tax);
			}
			
			
			if ($request->has('loadType') && $request->filled('loadType')){
				if (in_array($request->loadType,['active','pending'])){
					$query = $query->where('status',$request->loadType);
				}else if ($request->loadType == 'kits'){
					$query = $query->where('is_kit',true);
				}
				
			}
			
			
			return $query->orderBy('id','desc')->paginate(20);
			
			# code...
		}
		
		public function table()
		{
			
			
			return Item::take(20)->get();
		}
		
		public function finditems($search,Request $request)
		{
			
			
			if ($request->has('search_for') && $request->search_for == 'sale'){
				
				$items = Item::lastFiveSearch($search)->get();
				
				if (count($items) == 0){
					$itemby_serial = Item::itemBySerialSearch($search);
					if (!empty($itemby_serial)){
						$items = $itemby_serial;
					}
					
				}
				
				
			}else{
				$items = Item::where('barcode','LIKE','%'.$search.'%')
					->orWhere('name','LIKE','%'.$search.'%')
					->orWhere('ar_name','LIKE','%'.$search.'%')
					->take(5)->get();
			}
			return $items;
		}
		
		public function create()
		{
			$isClone = false;
			$cats = Category::mainOnly()->with('children.children.children.children.children.children')->get();
			$categories = $cats->each(function ($category){
				$category['label'] = $category['locale_name'];
				$category['children']->each(function ($scategory){
					$scategory['label'] = $scategory['locale_name'];
					$scategory['children']->each(function ($sscategory){
						$sscategory['label'] = $sscategory['locale_name'];
						$sscategory['children']->each(function ($ssscategory){
							$ssscategory['label'] = $ssscategory['locale_name'];
						});
					});
				});
			});
			
			
			$vendors = User::where('is_vendor',true)->get();


//			return $vendors;
			
			return view('items.create2',compact('categories','isClone','vendors'));
		}
		
		public function clone(Item $item)
		{
			//
			
			
			$isClone = true;
			$cats = Category::mainOnly()->with('children.children.children.children.children.children')->get();
			
			$categories = $cats->each(function ($category){
				$category['label'] = $category['locale_name'];
				$category['children']->each(function ($scategory){
					$scategory['label'] = $scategory['locale_name'];
					$scategory['children']->each(function ($sscategory){
						$sscategory['label'] = $sscategory['locale_name'];
						$sscategory['children']->each(function ($ssscategory){
							$ssscategory['label'] = $ssscategory['locale_name'];
						});
					});
				});
			});
			
			
			$vendors = User::where('is_vendor',true)->get();
			// return $item;
			// return  $item->filters;
			return view('items.create2',compact('categories','isClone','item','vendors'));
		}
		
		public function store(CreateItemRequest $request)
		{
			
			
			return $request->save();
			//
		}
		
		public function show(Item $item)
		{
			
			return view('items.show',compact('item'));
			//
		}
		
		public function edit(Item $item)
		{
			$isClone = true;
			$isEdited = true;
			$cats = Category::mainOnly()->with('children.children.children.children.children.children')->get();
			
			$categories = $cats->each(function ($category){
				$category['label'] = $category['locale_name'];
				$category['children']->each(function ($scategory){
					$scategory['label'] = $scategory['locale_name'];
					$scategory['children']->each(function ($sscategory){
						$sscategory['label'] = $sscategory['locale_name'];
						$sscategory['children']->each(function ($ssscategory){
							$ssscategory['label'] = $ssscategory['locale_name'];
						});
					});
				});
			});
			
			
			$vendors = User::where('is_vendor',true)->get();
			// return $item;
			// return  $item->filters;
			return view('items.create2',compact('categories','isClone','item','isEdited','vendors'));
			
			
			//
		}
		
		public function update(UpdateItemRequest $request,Item $item)
		{
			
			
			return $request->save($item);
			//
		}
		
		public function destroy(Item $item)
		{
			$item->delete();
			return back();
			//
		}
		
		public function activate(Item $item)
		{
			$item->update([
				'status' => 'active'
			]);
			return back();
			//
		}
		
		public function checkBarcodeIfItAlreadyUsed(ItemCheckRequest $request)
		{
		
		}
		
		public function checkserial(CheckItemSerialRequest $request)
		{
			
			$request->validate([
				'serial' => [
					function ($attr,$value,$fail){
						$serial = ItemSerials::where('serial',$value)->first();
						if (!empty($serial)){
							if (in_array($serial->current_status,['available','r_sale'])){
								$fail('this serial is already used');
							}
						}
					}
				]
			]);
		}
		
		public function checkserialavailable(CheckItemSerialRequest $request)
		{
			
			$request->validate(['serial' => ['required','string','exists:item_serials',
				function ($attr,$value,$fail){
					$serial = ItemSerials::where('serial',$value)->first();
					if (in_array($serial->current_status,['available','r_sale','r_purchase'])){
						$fail('this serial is already used');
					}
				}
			]
			]);
			
		}
		
		public function checkserialforsale(CheckItemSerialRequest $request)
		{
			
			$request->validate(['serial' => ['required','string','exists:item_serials',
				function ($attr,$value,$fail){
					
					$serial = ItemSerials::where('serial',$value)->first();
					if (empty($serial)){
						$fail('no serial like this');
					}else{
						if (in_array($serial->current_status,['saled','r_purchase'])){
							$fail('this serial is already used');
						}
					}
					
				}
			]
			]);
			
			return;
			
		}
		
		public function movement(Item $item,Request $request)
		{
			if ($request->has('startDate') && $request->filled('startDate') && $request->has('endDate') &&
				$request->filled('endDate')){
				return $item->stockMovement(['startDate' => $request->startDate,'endDate' => $request->endDate]);
			}
			
			return collect($item->stockMovement());
			
		}
		
		public function barcode()
		{
			return view('items.barcode.index');
		}
		
		public function barcode_show(Request $request)
		{
			$request->validate([
				'barcode' => 'required|exists:items,barcode'
			]);
			$item = Item::where('barcode',$request->barcode)->first();

			return view('items.barcode.show',compact('item'));
		}
		
		public function view_serials(Item $item)
		{
			
			$serials = $item->serials()->paginate(15);
			
			return view('items.serials',compact('serials'));
			
		}
	}
