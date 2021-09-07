<?php
	
	namespace App\Http\Controllers\App\API\Notifications;
	
	use App\Http\Controllers\Controller;
	use App\Models\Order;
	use Illuminate\Http\Request;
	
	class OrderNotificationController extends Controller
	{
		public function pending(Request $request)
		{
			return Order::where('status', 'pending')->with('user', 'paymentDetail','draftInvoice')->get();
		}
		
		public function paid(Request $request)
		{
			return Order::where('status', 'paid')->with('user', 'paymentDetail','draftInvoice')->get();
		}
	}
