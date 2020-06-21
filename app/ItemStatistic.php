<?php

namespace App;


class ItemStatistic extends BaseModel
{
	protected $guarded = [];
	
	public function item()
	{
		return $this->belongsTo(Item::class,'item_id');
	}
    //
}
