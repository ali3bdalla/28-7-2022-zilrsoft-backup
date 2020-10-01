<?php

namespace App\Http\Requests\Inventories;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreInventoryAdjustmentRequest extends FormRequest
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
            'items' => 'required|array',
            'items.*.id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer',
            'items.*.serials' => 'nullable|array',
            'items.*.serials.*' => 'nullable|exists:item_serials,serial',
        ];
    }


    public function store()
    {
        DB::beginTransaction();

        try{

            return $this->all();

            DB::commit();
        }catch(Exception $ex)
        {
            DB::rollBack();

            throw $ex;
        }
    }
}
