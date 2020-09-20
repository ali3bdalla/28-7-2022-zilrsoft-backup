<?php
	
	namespace App\Http\Requests\Accounting\Kit;
	
	use Exception;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class UpdateKitRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('manage kit');
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
				'name' => 'required|string',
				'ar_name' => 'required|string',
				'items' => 'required|array',
				'items.*.id' => 'required|integer|exists:items,id',
				'items.*.price' => 'required|numeric',
				'items.*.total' => 'required|numeric',
				'items.*.tax' => 'required|numeric',
				'items.*.subtotal' => 'required|numeric',
				'items.*.net' => 'required|numeric',
				'items.*.discount' => 'required|numeric',
				'total' => 'required|numeric',
				'subtotal' => 'required|numeric',
				'discount' => 'required|numeric',
				'discount_percent' => 'required|numeric',
				'tax' => 'required|numeric',
				'net' => 'required|numeric',
				'remaining' => 'required|numeric'
			
			];
		}
		
		public function save($kit)
		{
			
			
			$invoice = null;
			DB::beginTransaction();
			try{
				$user = $this->user();
				$data = $this->only('name','ar_name');
				$kit->update($data);
				$kit->items()->delete();
				$kit->data()->delete();
				$kit->addKitChildren($this->items);
				$kit->fillKitData($this->all());
				DB::commit();
				
				
			}catch (Exception $e){
				DB::rollBack();
				throw new Exception($e->getMessage());
			}
			
			
		}
	}
