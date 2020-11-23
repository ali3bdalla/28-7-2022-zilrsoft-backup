<?php
	
	namespace App\Http\Requests\Web\Order;
	
	use App\Events\Order\ClientUpdateOrderPaymentEvent;
	use App\Models\Order;
	use Illuminate\Foundation\Http\FormRequest;
	
	class ConfirmOrderPaymentRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return true;
		}
		
		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules()
		{
			return [
				//
				'first_name' => 'required|string|min:3',
				'last_name' => 'required|string|min:3',
				'sender_bank_id' => 'required|integer|exists:banks,id',
				'sender_account_number' => 'required|string|min:3|max:30',
				'receiver_bank_id' => 'required|integer|exists:banks,id',
			];
		}
		
		public function confirm(Order $order)
		{
			$order->update(
				[
					'status' => 'pending'
				]
			);
			foreach($order->itemsQtyHolders as $holdQty) {
//				UpdateAvailableQtyByInvoiceItemJob::dispatchNow($holdQty->invoiceItem, true);
				$holdQty->update(
					[
						'status' => 'pending'
					]
				);
			}
			event(new ClientUpdateOrderPaymentEvent($order));
		}
	}
