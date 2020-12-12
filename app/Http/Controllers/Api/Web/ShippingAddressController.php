<?php
	
	namespace App\Http\Controllers\Api\Web;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Web\ShippingAddress\StoreShippingAddressRequest;
	use App\Models\ShippingAddress;
	use Illuminate\Http\Request;
	use Illuminate\Validation\ValidationException;
	
	class ShippingAddressController extends Controller
	{
		
		public function store(StoreShippingAddressRequest $request)
		{
			return $request->store();
		}
		
		public function index(Request $request)
		{
			return $request->user('client')->shippingAddress;
		}
		
		public function destroy(ShippingAddress $shippingAddress)
		{
			$this->shippingAddressShouldBelongToLoggedUser($shippingAddress);
			$shippingAddress->delete();
		}
		
		private function shippingAddressShouldBelongToLoggedUser(ShippingAddress $shippingAddress)
		{
			if($shippingAddress->user_id != auth('client')->user()->id) {
				throw ValidationException::withMessages(
					[
						'shipping_address' => 'shipping Address Not Belong To Logged User'
					]
				);
			}
		}
	}
