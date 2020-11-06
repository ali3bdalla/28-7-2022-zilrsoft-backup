<?php
	
	namespace App\Http\Controllers\Api\Web;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Order\StoreOrderRequest;
	use Illuminate\Http\Request;
	
	class OrderController extends Controller
	{
		/**
		 * @param StoreOrderRequest $storeOrderRequest
		 * @return StoreOrderRequest
		 */
		public function store(StoreOrderRequest $storeOrderRequest)
		{
			return $storeOrderRequest->store();
		}
	}
