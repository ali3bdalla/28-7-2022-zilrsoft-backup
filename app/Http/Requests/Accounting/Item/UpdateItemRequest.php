<?php

namespace App\Http\Requests\Accounting\Item;

use App\Jobs\Items\Tag\UpdateItemTagsJob;
use App\Jobs\Utility\Str\ReplaceArabicSensitiveCharJob;
use App\Models\CategoryFilters;
use App\Models\Filter;
use App\Models\FilterValues;
use App\Models\ItemFilters;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

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
            'category_id' => 'required|integer|exists:App\Models\Category,id',
            'is_fixed_price' => 'required',
            'is_has_vtp' => 'required',
            'is_has_vts' => 'required',
            'is_need_serial' => 'required',
            'is_service' => 'required',
            'vtp' => 'required|numeric',
            'vts' => 'required|numeric',
            'vts_for_print' => 'numeric',
            'vtp_for_print' => 'numeric',
            'price' => 'required|numeric',
            'price_with_tax' => 'required|numeric',
            'warranty_subscription_id' => 'required|integer',
            'online_offer_price' => 'required_if:is_available_online,true',
            'is_available_online' => 'required_if:is_available_online,true',
            'weight' => 'required_if:is_available_online,true',
            'shipping_discount' => 'required_if:is_available_online,true',
            "description" => "required_if:is_available_online,true|string",
            "ar_description" => "required_if:is_available_online,true|string",
            'tags' => 'nullable|array',
            'tags.*' => 'required|string'

        ];
    }

    public function save($item)
    {
        $data = $this->only(
            'vtp_for_print',
            'vts_for_print',
            'name',
            "description",
            "ar_description",
            'ar_name',
            'barcode',
            'category_id',
            'is_fixed_price',
            'is_has_vtp',
            'is_has_vts',
            'is_need_serial',
            'vtp',
            'vts',
            'price',
            'online_price',
            'online_offer_price',
            'is_available_online',
            'weight',
            'shipping_discount',
            'warranty_subscription_id',
            'price_with_tax',

        );
        $data['ar_name'] = ReplaceArabicSensitiveCharJob::dispatchSync($this->input('ar_name'));

        $item->update($data);

        $item->filters()->delete();

        if ($item->attachments()->count() < 4 && $this->input('is_available_online')) {
            throw ValidationException::withMessages(
                [
                    'message' => [
                        'يجب اضافة ٤ صور'
                    ]
                ]
            );
        }

        if (!empty($this->filters)) {
            foreach ($this->filters as $filter => $value) {
                if ($value != null) {
                    ItemFilters::create(
                        [
                            'organization_id' => $this->user()->organization_id,
                            'creator_id' => $this->user()->id,
                            'filter_id' => $filter,
                            'filter_value' => $value,
                            'item_id' => $item->id
                        ]
                    );

                    $value_obj = FilterValues::find($value);
                    if (!empty($value_obj))
                        $value_obj->setAsLastUsedValue();
                }
            }
        }
        UpdateItemTagsJob::dispatchSync($item, (array)$this->input('tags'));
        if (auth()->user()->organization_id == 1 && $this->input('is_available_online')) {

            $requiredFilter = Filter::where('is_required_filter', true)->pluck('id')->toArray();
            $itemFilters = ItemFilters::where('item_id', $item->id)->pluck('filter_id')->toArray();

            foreach ($requiredFilter as $filterId) {
                if (!in_array($filterId, $itemFilters)) {
                    throw ValidationException::withMessages(
                        [
                            'message' => [
                                "يجب اختيار رقم الموديل"
                            ]
                        ]
                    );
                }
            }
            $itemDb = $item->fresh();

            if ($itemDb->shouldBeSearchable())
                $itemDb->searchable();
            else
                $itemDb->unsearchable();

            $item->update([
                'last_purchase_id' => null,
                'is_published' => $itemDb->shouldBeSearchable()
            ]);
        }

        return $item;
    }
}
