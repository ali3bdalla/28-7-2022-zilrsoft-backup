<?php

namespace App\Http\Requests\Items;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

/**
 * @property mixed item_id
 */
class ValidateSerialRequest extends FormRequest
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
            'serials' => 'array|required',
            'serials.*' => 'string|min:1',
            'item_id' => 'required|integer|organization_exists:App\Models\Item,id',

        ];
    }

    public function sale()
    {

        $item = Item::find($this->input('item_id'));
        return $item->serials()
            ->whereIn('status', ['in_stock', 'return_sale'])
            //stock_adjustment
            ->whereIn('serial', $this->input('serials'))
            ->pluck('serial');

    }

    public function returnSale()
    {

        $item = Item::find($this->input('item_id'));
        return $item->serials()
            ->whereIn('status', ['sold'])
            ->whereIn('serial', $this->input('serials'))
            ->pluck('serial');

    }


    public function purchase()
    {

        $item = Item::find($this->input('item_id'));
        $dbSerial = $item->serials()
            ->whereIn('status', ['in_stock', 'return_sale'])
            ->whereIn('serial', $this->input('serials'))
            ->first();

        if ($dbSerial != null) {
            throw  ValidationException::withMessages([
                'serial' => 'invalid serial'
            ]);
        }
    }


    public function returnPurchase()
    {

        $item = Item::find($this->input('item_id'));
        return $item->serials()
            ->whereIn('status', ['sold'])
            ->whereIn('serial', $this->input('serials'))
            ->pluck('serial');

    }


}
