<?php

namespace App\Http\Requests\Backend\Inventory;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Invoice;
class FetchInventoryAdjustmentsRequest extends FormRequest
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
        ];
    }

    public function getData()
    {
        $query = Invoice::where('invoice_type','inventory_adjustment');
			
        if ($this->has('id') && $this->filled('id')){
            $query = $query->where('id',$this->id);
        }
        
        if ($this->has('isDeleted') && $this->filled('isDeleted')){
            $query = $query->where('is_deleted',$this->input("isDeleted"));
        }

//
        if ($this->has('orderBy') && $this->filled('orderBy') && $this->has('orderType') && $this->filled('orderType')){
            $query = $query->orderBy($this->orderBy,$this->orderType);
        }else{
            $query = $query->orderByDesc("id");
        }
        
        
        if ($this->has('itemsPerPage') && $this->filled('itemsPerPage') && intval($this->input("itemsPerPage")
            ) >= 1 && intval($this->input('itemsPerPage')) <= 100){
            return $query->with('creator','items')->paginate(intval($this->input('itemsPerPage')));
        }else{
            return $query->with('creator','items')->paginate(20);
            
        }
        
        
    }
}
