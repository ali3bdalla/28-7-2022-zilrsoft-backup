<?php

namespace App\Http\Requests\Accounting\Item;

use App\Jobs\Items\Tag\UpdateItemTagsJob;
use App\Models\Attachment;
use App\Models\Filter;
use App\Models\FilterValues;
use App\Models\Item;
use App\Models\ItemFilters;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * @property mixed warranty_subscription_id
 */
class CreateItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create item');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'ar_name' => 'required|string',
            'barcode' => 'required',
            'category_id' => 'required|integer|organization_exists:App\Models\Category,id',
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
            'warranty_subscription_id' => 'required|integer',
            'online_offer_price' => 'required_if:is_available_online,true',
            'is_available_online' => 'required_if:is_available_online,true',
            'weight' => 'required_if:is_available_online,true',
            'shipping_discount' => 'required_if:is_available_online,true',
            "description" => "required_if:is_available_online,true",
            "ar_description" => "required_if:is_available_online,true",
            'tags' => 'nullable|array',
            'tags.*' => 'required|string',
            'images' => "nullable|array",
            "images.*.id" => "required|integer|exists:attachments,id",
        ];
    }

    /**
     * @throws ValidationException
     */
    public function save()
    {

        $data = $this->only('vtp_for_print',
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
            'price_with_tax');
        $data['organization_id'] = $this->user()->organization_id;
        $data['creator_id'] = $this->user()->id;
        $data['is_kit'] = false;
        $data['warranty_subscription_id'] = $this->warranty_subscription_id;
        $data['slug'] = Str::of($data['name'])->append(" ", rand(1000, 9999))->slug();
        if (!$this->user()->can('edit item')) {
            $data['status'] = 'pending';
        }
        $item = Item::create($data);
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
        if ($this->has('tags') && $this->filled('tags')) {
            UpdateItemTagsJob::dispatchSync($item, $this->input('tags'));
        }

        if ($this->filled('images')) {
            foreach ($this->input('images') as $key => $image) {
                Attachment::where('id', $image['id'])->update([
                    'attachable_type' => "App\\Models\\Item",
                    'attachable_id' => $item->id
                ]);
            }
        }
        $requiredFilter = Filter::where('is_required_filter', true)->pluck('id')->toArray();
        $itemFilters = ItemFilters::where('item_id', $item->id)->pluck('filter_id')->toArray();

        foreach ($requiredFilter as $filterId) {
            if (!in_array($filterId, $itemFilters)) {
                throw ValidationException::withMessages(
                    [
                        'filters' => [
                            'this filter should be selected'
                        ]
                    ]
                );
            }
        }

        if ($this->user()->organization_id == 1) {
            $itemDb = $item->fresh();
            if ($itemDb->shouldBeSearchable())
                $itemDb->searchable();
            $item->update([
                'is_published' => $itemDb->shouldBeSearchable()
            ]);
        }
        return $item;
    }
}
