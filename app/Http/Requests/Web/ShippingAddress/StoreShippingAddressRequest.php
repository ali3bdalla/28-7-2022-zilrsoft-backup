<?php
	
	namespace App\Http\Requests\Web\ShippingAddress;
	
	use Illuminate\Database\QueryException;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class StoreShippingAddressRequest extends FormRequest
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
				'building_number' => 'nullable|string',
				'city' => 'required|string',
				'first_name' => 'required|string',
				'last_name' => 'required|string',
				'country_id' => 'required|integer|exists:countries,id',
				'zip_code' => 'nullable|string',
				'description' => 'nullable|string',
				'street_name' => 'nullable|string',
				'phone_number' => 'required|string'
			];
		}
		
		public function store()
		{
			DB::beginTransaction();
			
			
			try {
				
				$loggedUser = auth('client')->user();
				
				$shippingAddress = $loggedUser->shippingAddresses()->create(
					[
						'country_id' => $this->input('country_id'),
						'building_number' => $this->input('building_number'),
						'first_name' => $this->input('first_name'),
						'last_name' => $this->input('last_name'),
						'city' => $this->input('city'),
						'zip_code' => $this->input('zip_code'),
						'description' => $this->input('description'),
						'phone_number' => $this->input('phone_number'),
						'street_name' => $this->input('street_name'),
					]
				);
				DB::commit();
				return $shippingAddress;
			} catch(QueryException $queryException) {
				DB::rollBack();
				throw  $queryException;
			}
		}
	}
