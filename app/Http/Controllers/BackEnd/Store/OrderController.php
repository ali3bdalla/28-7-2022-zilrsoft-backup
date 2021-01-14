<?php
	
	namespace App\Http\Controllers\BackEnd\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
	use Illuminate\Contracts\Foundation\Application;
	use Illuminate\Contracts\View\Factory;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	use Illuminate\View\View;
	
	class OrderController extends Controller
	{
		/**
		 * Display a listing of the resource.
		 *
		 * @param Request $request
		 * @return Application|Factory|View
		 */
		public function index()
		{
			return view('orders.index');
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
		 * Display the specified resource.
		 *
		 * @param Order $order
		 * @return Response
		 */
		public function show(Order $order)
		{
			$order->load('paymentDetail', 'user');
			return view('orders.show', compact('order'));
		}
		
		public function confirm(Order $order)
		{
			if(!$order->status == 'paid') {
				return view('errors.custom');
			}
			
			$order->update(
				[
					'status' => 'in_progress',
					'managed_by_id' => auth()->user()->id
				]
			);
			
			return redirect('/sales/drafts/' . $order->draft_id . '/to_invoice');
		}
		
		
	}
