<?php
	
	namespace App\Http\Controllers\Api\Store;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Order\StoreOrderRequest;
	use Illuminate\Contracts\Foundation\Application;
	use Illuminate\Http\RedirectResponse;
	use Illuminate\Http\Request;
	use Illuminate\Routing\Redirector;
	use Illuminate\Validation\ValidationException;
	use Mpdf\Mpdf;
	
	class OrderController extends Controller
	{
		/**
		 * @param StoreOrderRequest $storeOrderRequest
		 * @return StoreOrderRequest|Application|RedirectResponse|Redirector
		 */
		public function store(StoreOrderRequest $storeOrderRequest)
		{
			return $storeOrderRequest->store();
		}
	}
