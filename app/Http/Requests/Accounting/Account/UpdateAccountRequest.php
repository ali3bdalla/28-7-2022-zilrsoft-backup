<?php
	
	namespace App\Http\Requests\Accounting\Account;
	
	use App\Account;
	use Illuminate\Foundation\Http\FormRequest;
	
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
				'parent_id' => 'required|integer|exists:accounts,id'
			];
		}
		
		public function update($account)
		
		{
			$parent = Account::find($this->parent_id);
			
			$data = $this->only('parent_id','name','ar_name');
			if ($this->has('is_gateway') && $this->filled('is_gateway'))
				$data['is_gateway'] = true;
			else
				$data['is_gateway'] = false;
			
			$data['type'] = $parent->type;
			
			$account->update($data);
		}
	}
