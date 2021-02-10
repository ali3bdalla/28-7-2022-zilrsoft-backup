<?php

namespace App\Http\Controllers\Web\Order;

use App\Http\Controllers\Controller;
use App\Jobs\Order\CancelOrderJob;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CancelOrderController extends Controller
{
	public function confirm(Request $request, Order $order)
	{
		if (!$this->isValidOrderStatus($order)) {
			return Inertia::render(
				'Web/Order/CancelOrder',
				[
					'order' => $order
				]
			);
		}

		dispatch_now(new CancelOrderJob($order, true));
	}

	public function showPage(Request $request, Order $order)
	{

		if (!$this->isValidOrderStatus($order)) {
			return Inertia::render(
				'Web/Order/OrderConfirmationExpired', [
					'order' => $order
				]
			);
		}
		//

		return Inertia::render(
			'Web/Order/CancelOrder',
			[

				'order' => $order
			]
		);
	}

	private function isValidOrderStatus($order)
	{
		return $order->status === 'issued';
	}
}
