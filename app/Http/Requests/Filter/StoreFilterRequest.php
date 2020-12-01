<?php
	
	namespace App\Http\Requests\Filter;
	
	use App\Models\Filter;
	use App\Rules\UnqiueRule;
	use Illuminate\Database\QueryException;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class StoreFilterRequest extends FormRequest
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
				'name' => ["required", "string", 'organization_unique:App\Models\Filter,name'],
				'ar_name' => ["required", "string", "organization_unique:App\Models\Filter,ar_name"],
				'is_required_filter' => 'nullable|boolean'
			];
		}
		
		public function store()
		{
			DB::beginTransaction();
			try {
				$loggedUser = $this->user();
				$data = $this->only('name', 'ar_name');
				if($this->has('is_required_filter')) {
					$data['is_required_filter'] = (boolean)$this->input('is_required_filter');
				} else {
					$data['is_required_filter'] = false;
				}
				
				$data['organization_id'] = $loggedUser->organization_id;
				$data['creator_id'] = $loggedUser->id;
				Filter::create($data);
				DB::commit();
				return redirect('/filters');
			} catch(QueryException $exception) {
				DB::rollBack();
				throw $exception;
			}
		}
	}
