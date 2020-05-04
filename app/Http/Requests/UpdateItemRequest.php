<?php

namespace App\Http\Requests;

use App\FilterValues;
use App\Http\Requests\Helper\AttachmentsUploaderHelper;
use App\Item;
use App\ItemFilters;
use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{

    use AttachmentsUploaderHelper;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {


        return $this->user()->isAuthorizedTo('update-item');
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
            'barcode' => 'required',
            'category_id' => 'required|integer|exists:categories,id',
            'is_fixed_price' => 'required',
            'is_has_vtp' => 'required',
            'is_has_vts' => 'required',
            'is_need_serial' => 'required',
            'is_service' => 'required',
            'vtp' => 'required|numeric',
            'vts' => 'required|numeric',
            'price' => 'required|numeric',
            'price_with_tax' => 'required|numeric',
            "attachments.*" => 'required|image'
        ];
    }

    public function save($item)
    {
        $data = $this->only('name', 'ar_name', 'barcode', 'category_id', 'is_fixed_price', 'is_has_vtp', 'is_has_vts', 'is_need_serial',
            'vtp', 'vts', 'price', 'price_with_tax');

        $item->update($data);


        if (!empty($this->attachments)) {
            $this->delete_attachments($item);
            $this->upload_attachments($item, 'items');
        }


        $item->filters()->delete();
        if (!empty($this->filters)) {
            foreach ($this->filters as $filter => $value) {
                if ($value != null) {
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
