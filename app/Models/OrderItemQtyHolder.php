<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemQtyHolder extends BaseModel
{
    protected $guarded = [];
	
    protected $table = "order_item_qty_holders";
    
	public function order()
	{
		return $this->belongsTo(Order::class,'order_id');
    }
	
	
	public function invoiceItem()
	{
		return $this->belongsTo(InvoiceItems::class,'item_id')->withoutGlobalScopes(["organization","manager",'draft']);
	}
}
