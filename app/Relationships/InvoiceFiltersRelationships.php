<?php
namespace App\Relationships;


use App\Item;
trait InvoiceFiltersRelationships {

	public function item(	)
	{
		return $this->belongsTo(Item::class,'item_id');
	}
}
