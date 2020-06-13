<?php

	namespace App\Http\Requests\Accounting\Item;

	use App\FilterValues;
	use App\ItemFilters;
	use Illuminate\Foundation\Http\FormRequest;

	class UpdateItemRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return $this->user()->can('edit item');
		}

		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules()
		{
			return [
				'name' => 'required|string',
				'ar_name' => 'required|string',
				'barcode' => 'required|min:4',
				'category_id' => 'required|integer|exists:categories,id',
				'is_fixed_price' => 'required',
				'is_has_vtp' => 'required',
				'is_has_vts' => 'required',
				'is_need_serial' => 'required',
				'is_service' => 'required',
				'vtp' => 'required|numeric',
				'vts' => 'required|numeric',
                'vts_for_print' => 'required|numeric',
                'vtp_for_print' => 'required|numeric',
				'price' => 'required|numeric',
				'price_with_tax' => 'required|numeric',
				'warranty_subscription_id' => 'required|integer',
			];
		}

		public function save($item)
		{
			$data = $this->only(
			    'vtp_for_print',
			    'vts_for_print',
				'name',
				'ar_name','barcode',
				'category_id','is_fixed_price',
				'is_has_vtp','is_has_vts',
				'is_need_serial',
				'vtp',
				'vts',
				'price',
				'warranty_subscription_id',
				'price_with_tax');

			$item->update($data);

			$item->filters()->delete();

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
