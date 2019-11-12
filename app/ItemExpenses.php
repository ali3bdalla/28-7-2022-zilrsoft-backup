<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemExpenses extends Model
{
	
	
	protected $guarded = [];
	
	public function expense()
	{
		
		return $this->belongsTo(Expense::class,'expense_id');
	}
    //
}
