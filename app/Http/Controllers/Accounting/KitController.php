<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Category;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Kit\CreateKitRequest;
	use App\Item;
	use App\Manager;
	use App\User;
	use Exception;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Http\RedirectResponse;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	use Illuminate\View\View;
	
	class KitController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			
			$this->middleware("permission:view item");
			
			$categories = Category::all();
			$creators = Manager::all();
			
			return view('accounting.kits.index',compact('categories','creators'));
			//
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			$clients = User::where('is_client',true)->get()->toArray();
			return view('accounting.kits.create',compact('clients'));
			//
		}
		
		/**
		 * @param CreateKitRequest $request
		 *
		 * @throws Exception
		 */
		public function store(CreateKitRequest $request)
		{
			return $request->save();
		}
		
		public function show(Item $kit)
		{
			$items =  $kit->items;
			$invoice = $kit->data;
			return view('accounting.kits.show',compact('kit','items','invoice'));
		}
		
		/**
		 * @param Item $kit
		 *
		 * @return Factory|View
		 */
		public function edit(Item $kit)
		{
			
			$items = $kit->items()->with('item')->get();
			$creator = $kit->creator;
			$data = $kit->data;
			$clients = User::where('is_client',true)->get()->toArray();
			$kit['items'] = $items;
			$kit['creator'] = $creator;
			$kit['data'] = $data;
			
			
			return view('kits.edit',compact('clients','kit'));
			//
		}
		
		/**
		 * @param Request $request
		 * @param Item $kit
		 */
		public function update(Request $request,Item $kit)
		{
			//
		}
		
		/**
		 * @param Item $kit
		 *
		 * @return RedirectResponse
		 * @throws Exception
		 */
		public function destroy(Item $kit)
		{
			$kit->items()->delete();
			$kit->data()->delete();
			$kit->delete();
			return back();
			//
		}
	}
