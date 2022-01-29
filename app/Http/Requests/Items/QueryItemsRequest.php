<?php

namespace App\Http\Requests\Items;

use App\Models\Item;
use App\Models\ItemSerials;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

class QueryItemsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'barcode_or_name_or_serial' => 'nullable'
        ];
    }

    public function results()
    {


        $limit = 6;
        if ($this->has('invoice_type') && $this->filled('invoice_type') && $this->input('invoice_type') == 'dashboard') {
            $limit = 10;
        }
        $query = Item::where('is_expense', false)->with('data', 'items', "attachments")->withCount(['pipeline' => function (Builder $query) {
            $query->where('invoice_type', 'sale');
        }])->orderBy('pipeline_count', 'desc')->limit($limit);


        if ($this->has('barcode_or_name_or_serial') && $this->filled('barcode_or_name_or_serial')) {
            $query = $query->where('barcode', 'ILIKE', '%' . $this->input('barcode_or_name_or_serial') . '%')
                ->orWhere('name', 'ILIKE', '%' . $this->input('barcode_or_name_or_serial') . '%')
                ->orWhere('ar_name', 'ILIKE', '%' . $this->input('barcode_or_name_or_serial') . '%');
        }
        $result = $query->take($limit)->get();

        if ($this->has('invoice_type') && $this->input("invoice_type") == 'sale') {
            if (count($result) == 0) {
                $serialData = ItemSerials::where('serial', $this->input('barcode_or_name_or_serial'))
                    ->whereIn('status', ['in_stock', 'return_sale'])
                    ->first();
                if (!empty($serialData)) {
                    $item = $serialData->item;
                    $item->has_init_serial = true;
                    $item->init_serial = $serialData->fresh();
                    $result[] = $item;
                }
            }
        }


        return $result;
    }
}
