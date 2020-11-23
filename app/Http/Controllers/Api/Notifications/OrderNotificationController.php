<?php
	
	namespace App\Http\Controllers\Api\Notifications;
	
	use App\Http\Controllers\Controller;
	use App\Models\Order;
	use Illuminate\Http\Request;
	
	class OrderNotificationController extends Controller
	{
		public function pending(Request $request)
		{
			return Order::where('status', 'pending')->with('user')->get();
		}
	}
