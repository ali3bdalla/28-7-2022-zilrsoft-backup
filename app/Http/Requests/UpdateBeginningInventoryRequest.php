<?php

namespace App\Http\Requests;

use App\Models\InvoiceItems;
use App\Models\Item;
use App\Models\ItemSerials;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateBeginningInventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
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
			
			'items'=>'required|array',
			'items.*.id'=>'required|exists:invoice_items,id',
			'items.*.item_id'=>'required|exists:items,id',
		
		];
	}
	
	public function save($inventory)
	{
	
		
		$invoice = null;
		DB::beginTransaction();
		try {
			
			
			
			foreach ($this->items as $item)
			{
				
				if($item['has_been_deleted']){
					
					$default =  InvoiceItems::find($item['id']);
					$variation = $default->qty - $item['qty'];
					$base =  Item::find($item['item_id']);
					$base->updateItemAvailableQty('sub',abs($variation));
					$base->serials()->where('item_serials.purchase_invoice_id',$inventory->invoice_id)->delete();
					$default->delete();
				}
				
			}

			DB::commit();
		} catch(\Exception $e)
		{
			DB::rollBack();
			throw new \Exception($e->getMessage());
		}
		
		
	}
}
