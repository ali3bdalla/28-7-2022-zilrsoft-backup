<?php
	
	namespace App\Http\Requests\Web\Order;
	
	use App\Events\Order\ClientUpdateOrderPaymentEvent;
	use App\Models\Order;
	use App\Models\OrderPaymentDetail;
	use Illuminate\Database\QueryException;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
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
				'sender_account_id' => 'required|integer|exists:user_gateways,id',
				'receiver_bank_id' => 'required|integer|exists:banks,id',
			];
		}
		
		public function confirm(Order $order)
		{
			DB::beginTransaction();
			
			try {
				$order->update(
					[
						'status' => 'pending'
					]
				);
				foreach($order->itemsQtyHolders as $holdQty) {
					$holdQty->update(
						[
							'status' => 'pending'
						]
					);
				}
				
				$order->paymentDetail()->create(
					[
						'user_id' => $order->user_id,
						'sender_account_id' => $this->input('sender_account_id'),
						'received_bank_id' => $this->input('receiver_bank_id'),
						'first_name' => $this->input('first_name'),
						'last_name' => $this->input('last_name'),
					]
				);

				DB::commit();
				event(new ClientUpdateOrderPaymentEvent($order));
				
			} catch(QueryException $exception) {
				DB::rollBack();
				throw  $exception;
			}
		}
	}
