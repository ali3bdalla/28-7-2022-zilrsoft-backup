<?php
	
	namespace App\Http\Requests\DeliveryMan;
	
	use App\Models\DeliveryMan;
	use Illuminate\Database\QueryException;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Str;
	
	class StoreDeliveryManRequest extends FormRequest
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
				'first_name' => 'required|string',
				'last_name' => 'required|string',
				'phone_number' => 'required|string',
				'id_number' => 'required|string',
				'city_id' => 'required|integer|exists:cities,id',
			];
		}
		
		public function store()
		{
			DB::beginTransaction();
			
			
			try {
				
				$data = $this->only('first_name', 'last_name', 'phone_number', 'city_id');
				$data['hash'] = Str::random(255);
				$data['organization_id'] =auth()->user()->organization_id;
				$data['creator_id'] = auth()->user()->id;
				
				DeliveryMan::create($data);
				
				
				DB::commit();
				
				return redirect(route('delivery_men.index'));
			} catch(QueryException $queryException) {
				DB::rollBack();
				throw $queryException;
			}
		}
	}
