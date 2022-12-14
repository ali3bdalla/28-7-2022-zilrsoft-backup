<?php
	
	namespace App\Http\Controllers\App\Web;
	
	use App\Models\Category;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Kit\CreateKitRequest;
	use App\Http\Requests\Accounting\Kit\UpdateKitRequest;
	use App\Models\Item;
	use App\Models\Manager;
	use App\Models\User;
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
			$items = $kit->items;
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
			
			return view('accounting.kits.edit',compact('kit','items','creator','data'));
			//
		}
		
		/**
		 * @param UpdateKitRequest $request
		 * @param Item $kit
		 */
		public function update(UpdateKitRequest $request,Item $kit)
		{
			//
			return $request->save($kit);
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
