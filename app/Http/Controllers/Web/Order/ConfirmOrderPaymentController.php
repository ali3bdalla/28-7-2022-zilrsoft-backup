<?php
	
	namespace App\Http\Controllers\Web\Order;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Web\Order\ConfirmOrderPaymentRequest;
	use App\Models\Bank;
	use App\Models\Order;
	use Illuminate\Http\Request;
	use Illuminate\Http\Response;
	use Inertia\Inertia;
	
	class ConfirmOrderPaymentController extends Controller
	{
		
		
		public function confirmPayment(ConfirmOrderPaymentRequest $request, Order $order)
		{
			
			if(!$this->isValidOrderStatus($order)) {
				return Inertia::render(
					'Web/Order/OrderConfirmationExpired', [
						'order' => $order
					]
				);
			}
			
			$request->confirm($order);
			
			
			return Inertia::render(
				'Web/Order/PaymentConfirmed', [
					'order' => $order
				]
			);
		}
		
		
		public function showConfirmPaymentPage(Request $request, Order $order)
		{
			
			if(!$this->isValidOrderStatus($order)) {
				return response()->view(
					'errors.custom', [
						'message' => 'Your Order Has  Been Canceled',
						'title' => 'Order Not Found'
					]
				);
			}
			
			
			return Inertia::render(
				'Web/Order/ConfirmPayment', [
					'order' => $order,
					'banks' => Bank::all()
				]
			);
		}
		
		
		private function isValidOrderStatus($order)
		{
			return $order->status === 'issued';
			
		}
	}
