<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DynamicFields extends Model
{
    //
	protected $guarded = [];
	
	public function dynamicable()
	{
		return $this->morphTo();
	}
}
