<?php
	
	namespace App\Http\Controllers\App\API;
	
	use App\Http\Controllers\Controller;
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
			return DeliveryMan::paginate(10);
		}
		
		/**
		 * Show the form for creating a new resource.
		 *
		 * @return Response
		 */
		public function create()
		{
			//
		}
		
		/**
		 * Store a newly created resource in storage.
		 *
		 * @param Request $request
		 * @return Response
		 */
		public function store(Request $request)
		{
			//
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
