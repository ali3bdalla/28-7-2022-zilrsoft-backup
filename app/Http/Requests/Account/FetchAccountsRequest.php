<?php
	
	namespace App\Http\Requests\Account;
	
	use App\Models\Account;
	use Illuminate\Foundation\Http\FormRequest;
	
	class FetchAccountsRequest extends FormRequest
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
			];
		}
		
		public function getData()
		{
			$query = new Account();
			
			
			if($this->has('parent_id') && $this->filled('parent_id')) {
				$query = $query->where('parent_id', $this->input('parent_id'));
			} else if(!$this->has('get') || !$this->filled('get') || !$this->input('get') == 'all') {
				$query = $query->where('parent_id', 0);
			}
			
			return $query->withCount('children')->get();
		}
	}
