<?php
namespace App\Relationships;


use App\Models\Item;
trait InvoiceFiltersRelationships {

	public function item(	)
	{
		return $this->belongsTo(Item::class,'item_id');
	}
}
