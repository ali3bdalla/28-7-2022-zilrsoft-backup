<?php
	
	namespace App\Http\Controllers\Accounting;
	
	use App\Category;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Accounting\Kit\CreateKitRequest;
	use App\Item;
	use App\Manager;
	use App\User;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
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
		
		
		public function store(CreateKitRequest $request)
		{
			return $request->save();
		}
		
		/**
		 * Show the form for editing the specified resource. and edit
		 *
		 * @param Item $item
		 *
		 * @return Response
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


//        return  $kit;
			
			
			return view('kits.edit',compact('clients','kit'));
			//
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param Item $item
		 *
		 * @return Response
		 */
		public function update(Request $request,Item $kit)
		{
			//
		}
		
		/**
		 * Remove the specified resource from storage.
		 *
		 * @param Item $item
		 *
		 * @return Response
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
