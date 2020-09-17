<?php

namespace App\Models;


class ItemExpenses extends BaseModel
{
	
	
	protected $guarded = [];
	
	public function expense()
	{
		
		return $this->belongsTo(Expense::class,'expense_id');
	}
    //
}
