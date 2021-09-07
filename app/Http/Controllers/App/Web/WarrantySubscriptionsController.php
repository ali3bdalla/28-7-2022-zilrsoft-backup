<?php
	
	namespace App\Http\Controllers\App\Web;
	
	use App\Http\Controllers\Controller;
	use App\Models\WarrantySubscription;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	
	class WarrantySubscriptionsController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @return Response
		 */
		public function index()
		{
			
			return WarrantySubscription::all();
			//
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
		 *
		 * @return Response
		 */
		public function store(Request $request)
		{
			//
		}
		
		/**
		 * Display the specified resource.
		 *
		 * @param WarrantySubscription $warrantySubscription
		 *
		 * @return Response
		 */
		public function show(WarrantySubscription $warrantySubscription)
		{
			//
		}
		
		/**
		 * Show the form for editing the specified resource.
		 *
		 * @param WarrantySubscription $warrantySubscription
		 *
		 * @return Response
		 */
		public function edit(WarrantySubscription $warrantySubscription)
		{
			//
		}
		
		/**
		 * Update the specified resource in storage.
		 *
		 * @param Request $request
		 * @param WarrantySubscription $warrantySubscription
		 *
		 * @return Response
		 */
		public function update(Request $request,WarrantySubscription $warrantySubscription)
		{
			//
		}
		
		/**
		 * Remove the specified resource from storage.
		 *
		 * @param WarrantySubscription $warrantySubscription
		 *
		 * @return Response
		 */
		public function destroy(WarrantySubscription $warrantySubscription)
		{
			//
		}
	}
