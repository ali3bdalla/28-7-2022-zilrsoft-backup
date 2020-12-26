<?php
	
	namespace App\Http\Requests\Accounting\Account;
	
	use App\Models\Account;
	use Illuminate\Foundation\Http\FormRequest;
	
	/**
	 * @property mixed parent_id
	 */
	class UpdateAccountRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('edit chart');
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
				'parent_id' => 'required|integer|organization_exists:App\Models\Account,id',
				"sorting_number" => 'required|integer'
			];
		}
		
		public function update($account)
		{
			$parent = Account::find($this->parent_id);
			
			$data = $this->only('parent_id', 'name', 'ar_name', 'sorting_number');
			$data['type'] = $this->input("account_type");
			
			if($this->has('is_gateway') && $this->filled('is_gateway'))
				$data['is_gateway'] = true;
			else
				$data['is_gateway'] = false;
			
			if($account->parent) {
				$account->parent->updateHashMap();
			}
			$account->updateHashMap();
			$account->updateSerial();
			
			
			$account->update($data);
		}
	}
