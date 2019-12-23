<?php
	
	namespace App\Http\Requests\Accounting\Item;
	
	use App\FilterValues;
	use App\Item;
	use App\ItemFilters;
	use Illuminate\Foundation\Http\FormRequest;
	
	class CreateItemRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('create item');
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
				'barcode' => 'required',
				'category_id' => 'required|integer|exists:categories,id',
				'is_fixed_price' => 'required',
				'is_has_vtp' => 'required',
				'is_has_vts' => 'required',
				'is_need_serial' => 'required',
				'is_expense' => 'required',
				'is_service' => 'required',
				'vtp' => 'required|numeric',
				'vts' => 'required|numeric',
				'price' => 'required|numeric',
				'price_with_tax' => 'required|numeric',
				'vendor_expense_id' => 'nullable|integer',
			];
		}
		
		public function save()
		{
			$data = $this->except('filters');
			$data['organization_id'] = $this->user()->organization_id;
			$data['creator_id'] = $this->user()->id;
			$data['is_kit'] = false;
			
			if(!$this->user()->can('edit item'))
			{
				$data['status'] = 'pending';
			}
			$item = Item::create($data);
			if (!empty($this->filters)){
				foreach ($this->filters as $filter => $value){
					if ($value != null){
						ItemFilters::create([
							'organization_id' => $this->user()->organization_id,
							'creator_id' => $this->user()->id,
							'filter_id' => $filter,
							'filter_value' => $value,
							'item_id' => $item->id
						]);
						$value_obj = FilterValues::find($value);
						if (!empty($value_obj))
							$value_obj->setAsLastUsedValue();
					}
				}
			}
			
			return $item;
			
		}
	}
