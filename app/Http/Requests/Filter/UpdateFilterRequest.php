<?php
	
	namespace App\Http\Requests\Filter;
	
	use App\Models\Filter;
	use App\Rules\UnqiueRule;
	use Illuminate\Database\QueryException;
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Support\Facades\DB;
	
	class UpdateFilterRequest extends FormRequest
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
		
		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules()
		{
			return [
				'name' => ["required", "string"],
				'ar_name' => ["required", "string"],
				'is_required_filter' => 'nullable|boolean'
			];
		}
		
		public function update(Filter $filter)
		{
			DB::beginTransaction();
			try {
				$data = $this->only('name', 'ar_name');
				if($this->has('is_required_filter')) {
					$data['is_required_filter'] = (boolean)$this->input('is_required_filter');
				} else {
					$data['is_required_filter'] = false;
				}
				$filter->update($data);
				DB::commit();
				return redirect('/filters');
			} catch(QueryException $exception) {
				DB::rollBack();
				throw $exception;
			}
		}
	}
