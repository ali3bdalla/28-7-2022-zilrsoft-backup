<?php

namespace App\Http\Requests\Account;

use App\Models\Account;
use Illuminate\Foundation\Http\FormRequest;

class StoreAccountRequest extends FormRequest
{
    /**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('create chart');
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
				'name' => 'required|string|organization_unique:App\Models\Account',
				'ar_name' => 'required|string|organization_unique:App\Models\Account',
				'parent_id' => 'required|integer|organization_exists:App\Models\Account,id',
				'account_type' => 'required|in:credit,debit'
			];
		}
		
		public function store()
		{
			$parent = Account::find($this->parent_id);
            
			$data = $this->only('parent_id','name','ar_name');
			$data['organization_id'] = $this->user()->organization_id;
			$data['type'] = $this->account_type;
			$data['slug'] = $parent->slug;
			$data['creator_id'] = $this->user()->id;

			if ($this->has('is_gateway') && $this->filled('is_gateway'))
				$data['is_gateway'] = true;
			else
				$data['is_gateway'] = false;
		

            $account = Account::create($data);
    
            return $account;
		}
}
