<?php
	
	namespace App\Http\Controllers;
	
	use App\Http\Requests\DeliveryMan\StoreDeliveryManRequest;
	use App\Models\City;
	use App\Models\DeliveryMan;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class DeliveryManController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			return view('delivery_men.index');
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			
			$cities = City::all();
			return view('delivery_men.create', compact('cities'));
			
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param StoreDeliveryManRequest $request
		 * @return void
		 */
		public function store(StoreDeliveryManRequest $request)
		{
			return $request->store();
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param DeliveryMan $deliveryMan
		 * @return Response
		 */
		public function show(DeliveryMan $deliveryMan)
		{
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param DeliveryMan $deliveryMan
		 * @return Response
		 */
		public function edit(DeliveryMan $deliveryMan)
		{
			//
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param DeliveryMan $deliveryMan
		 * @return Response
		 */
		public function update(Request $request, DeliveryMan $deliveryMan)
		{
			//
		}
		
		/**
		 * Remove the specified resource from storage.
		 *
		 * @param DeliveryMan $deliveryMan
		 * @return Response
		 */
		public function destroy(DeliveryMan $deliveryMan)
		{
			//
		}
	}
